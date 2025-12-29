<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $query = Category::query();

        // Tìm kiếm
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $categories = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'status' => 'nullable|in:0,1',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'status' => (bool) $request->input('status', 0),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Thêm danh mục thành công');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
            'status' => 'nullable|in:0,1',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'status' => (bool) $request->input('status', 0),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Cập nhật danh mục thành công');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Kiểm tra xem có sản phẩm nào thuộc danh mục này không
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục vì còn sản phẩm thuộc danh mục này');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Xóa danh mục thành công');
    }
}

