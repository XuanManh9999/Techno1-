@extends('layouts.admin')

@section('title', 'Chi tiết bài viết')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="bi bi-file-text"></i> Chi tiết bài viết</h2>
        <p class="text-muted mb-0">Xem thông tin chi tiết bài viết</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i>
            Sửa
        </a>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>
            Quay lại
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h3 class="mb-3">{{ $post->title }}</h3>
                
                @if($post->featured_image)
                    <div class="mb-3">
                        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" 
                             class="img-fluid rounded">
                    </div>
                @endif

                @if($post->excerpt)
                    <div class="alert alert-light mb-3">
                        <strong>Mô tả ngắn:</strong> {{ $post->excerpt }}
                    </div>
                @endif

                <div class="mb-3">
                    <h5>Nội dung:</h5>
                    <div class="border p-3 rounded">
                        {!! $post->content !!}
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Thông tin</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Slug:</strong><br>
                            <code>{{ $post->slug }}</code>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Tác giả:</strong><br>
                            {{ $post->user->name }}
                        </div>
                        
                        <div class="mb-3">
                            <strong>Trạng thái:</strong><br>
                            @if($post->is_published)
                                <span class="badge bg-success">Đã xuất bản</span>
                            @else
                                <span class="badge bg-secondary">Bản nháp</span>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <strong>Lượt xem:</strong><br>
                            <span class="badge bg-info">{{ $post->views }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Ngày tạo:</strong><br>
                            {{ $post->created_at->format('d/m/Y H:i') }}
                        </div>
                        
                        @if($post->published_at)
                        <div class="mb-3">
                            <strong>Ngày xuất bản:</strong><br>
                            {{ $post->published_at->format('d/m/Y H:i') }}
                        </div>
                        @endif
                        
                        <div class="mb-3">
                            <strong>Cập nhật lần cuối:</strong><br>
                            {{ $post->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

