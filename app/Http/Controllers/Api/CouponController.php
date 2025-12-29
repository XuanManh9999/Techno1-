<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    /**
     * Validate and apply coupon code
     */
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = strtoupper(trim($request->code));
        
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không tồn tại',
            ], 404);
        }

        if (!$coupon->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không còn hiệu lực',
            ], 400);
        }

        // Calculate cart total
        $cartItems = Cart::with(['product', 'variant'])
            ->where('user_id', Auth::id())
            ->get();

        $cartTotal = $cartItems->sum(function ($item) {
            return $item->subtotal;
        });

        if ($coupon->min_purchase_amount && $cartTotal < $coupon->min_purchase_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng tối thiểu phải đạt ' . number_format($coupon->min_purchase_amount) . '₫ để sử dụng mã này',
            ], 400);
        }

        if (!$coupon->canBeUsedByUser(Auth::id())) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã sử dụng hết số lần cho phép của mã giảm giá này',
            ], 400);
        }

        $discountAmount = $coupon->calculateDiscount($cartTotal);
        $finalAmount = max(0, $cartTotal - $discountAmount);

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã giảm giá thành công',
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'name' => $coupon->name,
                'type' => $coupon->type,
                'value' => $coupon->value,
            ],
            'discount' => [
                'amount' => $discountAmount,
                'formatted' => number_format($discountAmount) . '₫',
            ],
            'totals' => [
                'subtotal' => $cartTotal,
                'subtotal_formatted' => number_format($cartTotal) . '₫',
                'discount' => $discountAmount,
                'discount_formatted' => number_format($discountAmount) . '₫',
                'total' => $finalAmount,
                'total_formatted' => number_format($finalAmount) . '₫',
            ],
        ]);
    }
}
