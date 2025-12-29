@extends('layouts.admin')

@section('title', 'Quản lý bài viết')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="bi bi-file-text"></i> Quản lý bài viết</h2>
        <p class="text-muted mb-0">Quản lý các bài viết và tin tức</p>
    </div>
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i>
        Thêm bài viết
    </a>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.posts.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tìm kiếm</label>
                <input type="text" name="search" class="form-control" 
                       value="{{ request('search') }}" 
                       placeholder="Tiêu đề, nội dung...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Trạng thái</label>
                <select name="is_published" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="1" {{ request('is_published') == '1' ? 'selected' : '' }}>Đã xuất bản</option>
                    <option value="0" {{ request('is_published') == '0' ? 'selected' : '' }}>Bản nháp</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Tìm kiếm
                </button>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Posts Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-list-ul"></i>
            Danh sách bài viết
            <span class="badge bg-primary ms-2">{{ $posts->total() }}</span>
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Tiêu đề</th>
                        <th style="width: 150px;">Tác giả</th>
                        <th style="width: 120px;">Trạng thái</th>
                        <th style="width: 100px;">Lượt xem</th>
                        <th style="width: 120px;">Ngày tạo</th>
                        <th style="width: 120px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                    <tr>
                        <td><strong>#{{ $post->id }}</strong></td>
                        <td>
                            <div>
                                <strong>{{ $post->title }}</strong>
                            </div>
                            @if($post->excerpt)
                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($post->excerpt, 80) }}</small>
                            @endif
                        </td>
                        <td>{{ $post->user->name }}</td>
                        <td>
                            @if($post->is_published)
                                <span class="badge bg-success">Đã xuất bản</span>
                            @else
                                <span class="badge bg-secondary">Bản nháp</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $post->views }}</span>
                        </td>
                        <td>{{ $post->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.posts.show', $post->id) }}" 
                                   class="btn btn-info" 
                                   title="Xem">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.posts.edit', $post->id) }}" 
                                   class="btn btn-warning" 
                                   title="Sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.posts.destroy', $post->id) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted mb-0">Không tìm thấy bài viết nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($posts->hasPages())
        <div class="admin-pagination">
            {{ $posts->links('vendor.pagination.custom') }}
        </div>
        @endif
    </div>
</div>
@endsection

