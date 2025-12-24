<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        // Tìm kiếm
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Lọc theo danh mục
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo thương hiệu
        if ($request->has('brand_id') && $request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        $categories = Category::where('status', true)->get();
        $brands = Brand::where('status', true)->get();
        
        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = Category::where('status', true)->get();
        $brands = Brand::where('status', true)->get();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|unique:products,sku',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'boolean',
            'featured' => 'boolean',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'short_description' => $request->short_description,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'sku' => $request->sku,
            'stock_quantity' => $request->stock_quantity,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'status' => $request->has('status'),
            'featured' => $request->has('featured'),
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Thêm sản phẩm thành công');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::where('status', true)->get();
        $brands = Brand::where('status', true)->get();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|unique:products,sku,' . $id,
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'boolean',
            'featured' => 'boolean',
        ]);

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'short_description' => $request->short_description,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'sku' => $request->sku,
            'stock_quantity' => $request->stock_quantity,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'status' => $request->has('status'),
            'featured' => $request->has('featured'),
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Xóa sản phẩm thành công');
    }
}

