<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $query = Brand::query();

        // Tìm kiếm
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $brands = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'description' => 'nullable|string',
            'status' => 'nullable|in:0,1',
        ]);

        Brand::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'status' => (bool) $request->input('status', 0),
        ]);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Thêm thương hiệu thành công');
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $id,
            'description' => 'nullable|string',
            'status' => 'nullable|in:0,1',
        ]);

        $brand->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'status' => (bool) $request->input('status', 0),
        ]);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Cập nhật thương hiệu thành công');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        
        // Kiểm tra xem có sản phẩm nào thuộc thương hiệu này không
        if ($brand->products()->count() > 0) {
            return back()->with('error', 'Không thể xóa thương hiệu vì còn sản phẩm thuộc thương hiệu này');
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')
            ->with('success', 'Xóa thương hiệu thành công');
    }
}

