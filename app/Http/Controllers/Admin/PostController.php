<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $query = Post::with('user');

        // Tìm kiếm
        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('excerpt', 'like', '%' . $searchTerm . '%')
                  ->orWhere('content', 'like', '%' . $searchTerm . '%');
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('is_published') && $request->is_published !== '') {
            $query->where('is_published', filter_var($request->is_published, FILTER_VALIDATE_BOOLEAN));
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|url|max:500',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        try {
            $data = [
                'title' => $request->title,
                'slug' => $request->slug ?: Str::slug($request->title),
                'excerpt' => $request->excerpt,
                'content' => $request->content,
                'user_id' => auth()->id(),
                'is_published' => $request->boolean('is_published'),
                'published_at' => $request->filled('published_at') ? $request->published_at : ($request->boolean('is_published') ? now() : null),
                'featured_image' => $request->filled('featured_image') ? $request->featured_image : null,
            ];

            Post::create($data);

            return redirect()->route('admin.posts.index')
                ->with('success', 'Thêm bài viết thành công');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra khi thêm bài viết: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $post = Post::with('user')->findOrFail($id);
        return view('admin.posts.show', compact('post'));
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug,' . $id,
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|url|max:500',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        try {
            $data = [
                'title' => $request->title,
                'slug' => $request->slug ?: Str::slug($request->title),
                'excerpt' => $request->excerpt,
                'content' => $request->content,
                'is_published' => $request->boolean('is_published'),
                'published_at' => $request->filled('published_at') ? $request->published_at : ($request->boolean('is_published') && !$post->published_at ? now() : $post->published_at),
                'featured_image' => $request->filled('featured_image') ? $request->featured_image : $post->featured_image,
            ];

            $post->update($data);

            return redirect()->route('admin.posts.index')
                ->with('success', 'Cập nhật bài viết thành công');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật bài viết: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        try {
            $post->delete();

            return redirect()->route('admin.posts.index')
                ->with('success', 'Xóa bài viết thành công');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi xóa bài viết: ' . $e->getMessage());
        }
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $path = $request->file('file')->store('posts/content', 'public');
            return response()->json([
                'location' => Storage::url($path)
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Upload failed'], 500);
        }
    }
}
