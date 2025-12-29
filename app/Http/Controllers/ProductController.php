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
        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            if (!empty($searchTerm)) {
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('short_description', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%');
                });
            }
        }

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Lọc theo thương hiệu
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        // Lọc theo giá
        if ($request->filled('price_min') && is_numeric($request->price_min) && $request->price_min > 0) {
            $query->whereRaw('COALESCE(sale_price, price) >= ?', [$request->price_min]);
        }
        if ($request->filled('price_max') && is_numeric($request->price_max) && $request->price_max > 0) {
            $query->whereRaw('COALESCE(sale_price, price) <= ?', [$request->price_max]);
        }

        // Sắp xếp mặc định
        $query->orderBy('featured', 'desc')->orderBy('created_at', 'desc');

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('status', true)->orderBy('name')->get();
        $brands = Brand::where('status', true)->orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    public function show($slug)
    {
        try {
            $product = Product::with(['category', 'brand'])
                ->where('slug', $slug)
                ->where('status', true)
                ->firstOrFail();

            // Load variants separately to avoid errors if table doesn't exist
            try {
                $product->load(['variants' => function($query) {
                    $query->where('status', true)->orderBy('sort_order');
                }]);
                
                // Calculate min and max prices for products with variants
                if ($product->variants && $product->variants->count() > 0) {
                    $variantPrices = $product->variants->map(function($variant) {
                        return $variant->sale_price ?? $variant->price ?? $product->final_price;
                    })->filter()->values();
                    
                    if ($variantPrices->count() > 0) {
                        $product->min_price = $variantPrices->min();
                        $product->max_price = $variantPrices->max();
                    } else {
                        $product->min_price = $product->final_price;
                        $product->max_price = $product->final_price;
                    }
                } else {
                    $product->min_price = $product->final_price;
                    $product->max_price = $product->final_price;
                }
            } catch (\Exception $e) {
                // If variants table doesn't exist, just continue without variants
                \Log::warning('Product variants not available: ' . $e->getMessage());
                $product->min_price = $product->final_price;
                $product->max_price = $product->final_price;
            }

            $relatedProducts = Product::with('category')
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->where('status', true)
                ->limit(4)
                ->get();

            return view('products.show', compact('product', 'relatedProducts'));
        } catch (\Exception $e) {
            \Log::error('ProductController show error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            abort(500, 'Có lỗi xảy ra khi tải sản phẩm. Vui lòng thử lại sau.');
        }
    }
}

