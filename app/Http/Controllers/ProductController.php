<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand'])->where('status', true);

        // Tìm kiếm
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Lọc theo danh mục
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // Lọc theo thương hiệu
        if ($request->has('brand')) {
            $query->where('brand_id', $request->brand);
        }

        // Lọc theo giá
        if ($request->has('price_min')) {
            $query->whereRaw('COALESCE(sale_price, price) >= ?', [$request->price_min]);
        }
        if ($request->has('price_max')) {
            $query->whereRaw('COALESCE(sale_price, price) <= ?', [$request->price_max]);
        }

        $products = $query->paginate(12);
        $categories = Category::where('status', true)->get();
        $brands = Brand::where('status', true)->get();

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'brand'])
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', true)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}

