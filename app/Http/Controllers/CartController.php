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
        $cartItems = Cart::with(['product', 'variant'])
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->subtotal;
        });

        return view('cart.index', compact('cartItems', 'total'));
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

        // Nếu có variant, kiểm tra variant
        if ($request->variant_id) {
            $variant = ProductVariant::where('product_id', $product->id)
                ->where('id', $request->variant_id)
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
            ->where('variant_id', $request->variant_id)
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
                'variant_id' => $request->variant_id,
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

