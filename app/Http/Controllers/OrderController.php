<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'province_id' => 'nullable|integer',
            'district_id' => 'nullable|integer',
            'ward_id' => 'nullable|integer',
            'address_detail' => 'nullable|string|max:255',
            'payment_method' => 'required|in:VNPAY,COD',
            'notes' => 'nullable|string',
            'coupon_id' => 'nullable|exists:coupons,id',
            'discount_amount' => 'nullable|numeric|min:0',
        ]);

        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Giỏ hàng trống');
        }

        DB::beginTransaction();
        try {
            $subtotalAmount = $cartItems->sum(function ($item) {
                return $item->subtotal;
            });

            if ($subtotalAmount <= 0) {
                return back()->with('error', 'Tổng tiền đơn hàng không hợp lệ');
            }

            // Validate and apply coupon if provided
            $discountAmount = 0;
            $couponId = null;
            
            if ($request->coupon_id && $request->discount_amount) {
                $coupon = Coupon::find($request->coupon_id);
                
                if ($coupon && $coupon->isActive()) {
                    // Re-validate coupon
                    if ($coupon->canBeUsedByUser(Auth::id())) {
                        $calculatedDiscount = $coupon->calculateDiscount($subtotalAmount);
                        
                        // Verify the discount amount matches
                        if (abs($calculatedDiscount - $request->discount_amount) < 0.01) {
                            $discountAmount = $calculatedDiscount;
                            $couponId = $coupon->id;
                        } else {
                            return back()->with('error', 'Mã giảm giá không hợp lệ. Vui lòng thử lại.');
                        }
                    } else {
                        return back()->with('error', 'Bạn đã sử dụng hết số lần cho phép của mã giảm giá này');
                    }
                } else {
                    return back()->with('error', 'Mã giảm giá không còn hiệu lực');
                }
            }

            $totalAmount = max(0, $subtotalAmount - $discountAmount);

            if ($totalAmount <= 0) {
                return back()->with('error', 'Tổng tiền đơn hàng không hợp lệ');
            }

            $paymentStatus = Order::PAYMENT_STATUS_PENDING;

            // Tạo order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                'subtotal_amount' => $subtotalAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'coupon_id' => $couponId,
                'status' => Order::STATUS_PENDING,
                'payment_status' => $paymentStatus,
                'payment_method' => $request->payment_method,
                'shipping_name' => $request->shipping_name,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'province_id' => $request->province_id,
                'district_id' => $request->district_id,
                'ward_id' => $request->ward_id,
                'address_detail' => $request->address_detail,
                'notes' => $request->notes,
            ]);

            // Increment coupon usage if applied
            if ($couponId && $coupon) {
                $coupon->incrementUsage();
            }

            // Tạo order items và cập nhật stock
            foreach ($cartItems as $cartItem) {
                // Kiểm tra stock
                $availableStock = $cartItem->variant 
                    ? $cartItem->variant->stock_quantity 
                    : $cartItem->product->stock_quantity;

                if ($availableStock < $cartItem->quantity) {
                    DB::rollBack();
                    return back()->with('error', "Sản phẩm '{$cartItem->product_name}' không đủ số lượng tồn kho.");
                }

                $price = $cartItem->variant 
                    ? $cartItem->variant->final_price 
                    : $cartItem->product->final_price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'variant_id' => $cartItem->variant_id,
                    'variant_attributes' => $cartItem->variant ? $cartItem->variant->attributes : null,
                    'quantity' => $cartItem->quantity,
                    'price' => $price,
                    'subtotal' => $cartItem->subtotal,
                ]);

                // Cập nhật số lượng tồn kho
                if ($cartItem->variant) {
                    $cartItem->variant->decrement('stock_quantity', $cartItem->quantity);
                } else {
                    $cartItem->product->decrement('stock_quantity', $cartItem->quantity);
                }
            }

            // Xóa giỏ hàng
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            // Nếu là COD, tạo payment record và chuyển sang processing
            if ($request->payment_method === 'COD') {
                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => 'COD',
                    'amount' => $totalAmount,
                    'status' => Payment::STATUS_PENDING,
                ]);

                $order->update([
                    'status' => Order::STATUS_PROCESSING,
                ]);

                return redirect()->route('orders.show', $order->id)
                    ->with('success', 'Đặt hàng thành công! Bạn sẽ thanh toán khi nhận hàng.');
            }

            // Nếu là VNPAY, chuyển đến trang thanh toán
            if ($request->payment_method === 'VNPAY') {
                // Kiểm tra config VNPAY
                $vnp_TmnCode = config('services.vnpay.tmn_code');
                $vnp_HashSecret = config('services.vnpay.hash_secret');
                
                if (empty($vnp_TmnCode) || empty($vnp_HashSecret)) {
                    return back()->with('error', 'Hệ thống thanh toán VNPAY chưa được cấu hình. Vui lòng liên hệ quản trị viên.');
                }

                return redirect()->route('payment.create', $order->id)
                    ->with('success', 'Đặt hàng thành công. Vui lòng thanh toán.');
            }

            return back()->with('error', 'Phương thức thanh toán không hợp lệ');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order creation error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'payment_method' => $request->payment_method,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage());
        }
    }
}

