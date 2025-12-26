<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        try {
            $cartItems = Cart::with(['product', 'variant'])
                ->where('user_id', Auth::id())
                ->get();

            // Filter out items with missing products
            $cartItems = $cartItems->filter(function ($item) {
                return $item->product !== null;
            });

            $total = $cartItems->sum(function ($item) {
                try {
                    return $item->subtotal ?? 0;
                } catch (\Exception $e) {
                    return 0;
                }
            });

            return view('cart.index', compact('cartItems', 'total'));
        } catch (\Exception $e) {
            \Log::error('Cart index error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('cart.index', [
                'cartItems' => collect([]),
                'total' => 0
            ])->with('error', 'Có lỗi xảy ra khi tải giỏ hàng. Vui lòng thử lại.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = null;
        $stockQuantity = $product->stock_quantity;

        // Chuyển đổi variant_id rỗng thành null
        $variantId = !empty($request->variant_id) ? $request->variant_id : null;

        // Nếu có variant, kiểm tra variant
        if ($variantId) {
            $variant = ProductVariant::where('product_id', $product->id)
                ->where('id', $variantId)
                ->where('status', true)
                ->firstOrFail();
            
            if (!$variant->isInStock()) {
                return back()->with('error', 'Biến thể sản phẩm đã hết hàng');
            }
            $stockQuantity = $variant->stock_quantity;
        } else {
            if (!$product->isInStock()) {
                return back()->with('error', 'Sản phẩm đã hết hàng');
            }
        }

        // Kiểm tra số lượng
        if ($request->quantity > $stockQuantity) {
            return back()->with('error', 'Số lượng sản phẩm không đủ');
        }

        // Tìm cart item với cùng product và variant
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->where('variant_id', $variantId)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($newQuantity > $stockQuantity) {
                return back()->with('error', 'Số lượng sản phẩm không đủ');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'variant_id' => $variantId,
                'quantity' => $request->quantity,
            ]);
        }

        return back()->with('success', 'Đã thêm vào giỏ hàng');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::with(['product', 'variant'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $stockQuantity = $cartItem->variant 
            ? $cartItem->variant->stock_quantity 
            : $cartItem->product->stock_quantity;

        if ($request->quantity > $stockQuantity) {
            return back()->with('error', 'Số lượng sản phẩm không đủ');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Đã cập nhật giỏ hàng');
    }

    public function destroy($id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->delete();

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng');
    }

    public function checkout()
    {
        $cartItems = Cart::with(['product', 'variant'])
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->subtotal;
        });

        return view('checkout', compact('cartItems', 'total'));
    }
}

