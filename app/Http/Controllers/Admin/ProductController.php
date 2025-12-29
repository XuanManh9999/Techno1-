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
            'image' => 'nullable|url|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|unique:products,sku',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'nullable|in:0,1',
            'featured' => 'nullable|in:0,1',
            'variants' => 'nullable|array',
            'variants.*.sku' => 'nullable|string|unique:product_variants,sku',
            'variants.*.name' => 'nullable|string|max:255',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
            'variants.*.status' => 'nullable|boolean',
            'variants.*.is_default' => 'nullable|boolean',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'short_description' => $request->short_description,
            'image' => $request->filled('image') ? $request->image : null,
            'price' => $request->price,
            'sale_price' => $request->filled('sale_price') && $request->sale_price !== '' ? $request->sale_price : null,
            'sku' => $request->sku,
            'stock_quantity' => $request->stock_quantity,
            'category_id' => $request->category_id,
            'brand_id' => $request->filled('brand_id') && $request->brand_id !== '' ? $request->brand_id : null,
            'status' => (bool) $request->input('status', 0),
            'featured' => (bool) $request->input('featured', 0),
        ]);

        // Xử lý variants nếu có
        if ($request->has('variants') && is_array($request->variants)) {
            $hasDefault = false;
            foreach ($request->variants as $index => $variantData) {
                if (empty($variantData['attributes']) || !is_array($variantData['attributes'])) {
                    continue;
                }

                // Tạo SKU tự động nếu không có
                if (empty($variantData['sku'])) {
                    $variantData['sku'] = $product->sku . '-' . ($index + 1);
                }

                // Đảm bảo có ít nhất một variant là default
                if (!$hasDefault && ($index === 0 || ($variantData['is_default'] ?? false))) {
                    $variantData['is_default'] = true;
                    $hasDefault = true;
                } else {
                    $variantData['is_default'] = false;
                }

                ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variantData['sku'],
                    'name' => $variantData['name'] ?? null,
                    'attributes' => $variantData['attributes'],
                    'price' => (!empty($variantData['price']) && $variantData['price'] !== '') ? $variantData['price'] : null,
                    'sale_price' => (!empty($variantData['sale_price']) && $variantData['sale_price'] !== '') ? $variantData['sale_price'] : null,
                    'stock_quantity' => $variantData['stock_quantity'] ?? 0,
                    'image' => (!empty($variantData['image']) && $variantData['image'] !== '') ? $variantData['image'] : null,
                    'is_default' => $variantData['is_default'] ?? false,
                    'status' => $variantData['status'] ?? true,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Thêm sản phẩm thành công');
    }

    public function edit($id)
    {
        $product = Product::with('variants')->findOrFail($id);
        // Load tất cả categories để đảm bảo category hiện tại được hiển thị
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'image' => 'nullable|url|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|unique:products,sku,' . $id,
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'nullable|in:0,1',
            'featured' => 'nullable|in:0,1',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.sku' => 'nullable|string',
            'variants.*.name' => 'nullable|string|max:255',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
            'variants.*.status' => 'nullable|boolean',
            'variants.*.is_default' => 'nullable|boolean',
        ]);

        // Validate unique SKU cho variants
        if ($request->has('variants') && is_array($request->variants)) {
            foreach ($request->variants as $index => $variantData) {
                if (!empty($variantData['sku'])) {
                    $variantId = $variantData['id'] ?? null;
                    $existingVariant = ProductVariant::where('sku', $variantData['sku'])
                        ->where('id', '!=', $variantId)
                        ->first();
                    
                    if ($existingVariant) {
                        return redirect()->back()
                            ->withErrors(['variants.' . $index . '.sku' => 'SKU đã tồn tại.'])
                            ->withInput();
                    }
                }
            }
        }

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'short_description' => $request->short_description,
            'image' => $request->filled('image') ? $request->image : $product->image,
            'price' => $request->price,
            'sale_price' => $request->filled('sale_price') && $request->sale_price !== '' ? $request->sale_price : null,
            'sku' => $request->sku,
            'stock_quantity' => $request->stock_quantity,
            'category_id' => $request->category_id,
            'brand_id' => $request->filled('brand_id') && $request->brand_id !== '' ? $request->brand_id : null,
            'status' => (bool) $request->input('status', 0),
            'featured' => (bool) $request->input('featured', 0),
        ]);

        // Xử lý variants
        if ($request->has('variants') && is_array($request->variants)) {
            $existingVariantIds = [];
            $hasDefault = false;

            foreach ($request->variants as $index => $variantData) {
                if (empty($variantData['attributes']) || !is_array($variantData['attributes'])) {
                    continue;
                }

                // Cập nhật variant hiện có hoặc tạo mới
                if (!empty($variantData['id'])) {
                    $variant = ProductVariant::where('id', $variantData['id'])
                        ->where('product_id', $product->id)
                        ->first();
                    
                    if ($variant) {
                        // Tạo SKU tự động nếu không có
                        if (empty($variantData['sku'])) {
                            $variantData['sku'] = $product->sku . '-' . ($index + 1);
                        }

                        // Đảm bảo có ít nhất một variant là default
                        if (!$hasDefault && ($variantData['is_default'] ?? false)) {
                            $variantData['is_default'] = true;
                            $hasDefault = true;
                        } else {
                            $variantData['is_default'] = false;
                        }

                        $variant->update([
                            'sku' => $variantData['sku'],
                            'name' => $variantData['name'] ?? null,
                            'attributes' => $variantData['attributes'],
                            'price' => (!empty($variantData['price']) && $variantData['price'] !== '') ? $variantData['price'] : null,
                            'sale_price' => (!empty($variantData['sale_price']) && $variantData['sale_price'] !== '') ? $variantData['sale_price'] : null,
                            'stock_quantity' => $variantData['stock_quantity'] ?? 0,
                            'image' => (!empty($variantData['image']) && $variantData['image'] !== '') ? $variantData['image'] : null,
                            'is_default' => $variantData['is_default'] ?? false,
                            'status' => $variantData['status'] ?? true,
                            'sort_order' => $index,
                        ]);
                        $existingVariantIds[] = $variant->id;
                    }
                } else {
                    // Tạo variant mới
                    if (empty($variantData['sku'])) {
                        $variantData['sku'] = $product->sku . '-' . ($index + 1);
                    }

                    if (!$hasDefault && ($index === 0 || ($variantData['is_default'] ?? false))) {
                        $variantData['is_default'] = true;
                        $hasDefault = true;
                    } else {
                        $variantData['is_default'] = false;
                    }

                    $newVariant = ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $variantData['sku'],
                        'name' => $variantData['name'] ?? null,
                        'attributes' => $variantData['attributes'],
                        'price' => (!empty($variantData['price']) && $variantData['price'] !== '') ? $variantData['price'] : null,
                        'sale_price' => (!empty($variantData['sale_price']) && $variantData['sale_price'] !== '') ? $variantData['sale_price'] : null,
                        'stock_quantity' => $variantData['stock_quantity'] ?? 0,
                        'image' => (!empty($variantData['image']) && $variantData['image'] !== '') ? $variantData['image'] : null,
                        'is_default' => $variantData['is_default'] ?? false,
                        'status' => $variantData['status'] ?? true,
                        'sort_order' => $index,
                    ]);
                    $existingVariantIds[] = $newVariant->id;
                }
            }

            // Xóa các variant không còn trong danh sách
            ProductVariant::where('product_id', $product->id)
                ->whereNotIn('id', $existingVariantIds)
                ->delete();
        } else {
            // Nếu không có variants, xóa tất cả variants cũ
            ProductVariant::where('product_id', $product->id)->delete();
        }

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

