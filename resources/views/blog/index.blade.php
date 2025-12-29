@extends('layouts.app')

@section('title', 'Blog - Techno1')

@section('content')
<!-- Blog Page Section -->
<section class="blog-page-section py-5">
    <div class="container">
        <!-- Page Header -->
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold mb-3">
                <i class="bi bi-journal-text text-primary"></i> Blog & Tin tức
            </h1>
            <p class="lead text-muted">Cập nhật những thông tin mới nhất về công nghệ và sản phẩm</p>
        </div>

        <!-- Search Form -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('blog.index') }}" class="row g-3">
                    <div class="col-md-10">
                        <input type="text" name="search" class="form-control" 
                               value="{{ request('search') }}" 
                               placeholder="Tìm kiếm bài viết...">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Tìm kiếm
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Blog Posts Grid -->
        @if($posts->count() > 0)
        <div class="row g-4">
            @foreach($posts as $post)
            <div class="col-md-6 col-lg-4">
                <article class="card h-100 shadow-sm blog-card">
                    @if($post->featured_image)
                        <a href="{{ route('blog.show', $post->slug) }}">
                            <img src="{{ Storage::url($post->featured_image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $post->title }}"
                                 style="height: 200px; object-fit: cover;">
                        </a>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $post->published_at->format('d/m/Y') }}
                                <span class="ms-2">
                                    <i class="bi bi-eye me-1"></i>{{ $post->views }}
                                </span>
                            </small>
                        </div>
                        <h5 class="card-title">
                            <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none text-dark">
                                {{ $post->title }}
                            </a>
                        </h5>
                        @if($post->excerpt)
                            <p class="card-text text-muted flex-grow-1">{{ \Illuminate\Support\Str::limit($post->excerpt, 120) }}</p>
                        @endif
                        <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline-primary mt-auto">
                            Đọc thêm <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </article>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($posts->hasPages())
        <div class="mt-5">
            {{ $posts->links('vendor.pagination.custom') }}
        </div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
            <p class="text-muted">Không tìm thấy bài viết nào</p>
        </div>
        @endif
    </div>
</section>

@push('styles')
<style>
.blog-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    overflow: hidden;
}

.blog-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.blog-card .card-img-top {
    transition: transform 0.3s ease;
}

.blog-card:hover .card-img-top {
    transform: scale(1.05);
}

.blog-card .card-title a:hover {
    color: var(--primary) !important;
}
</style>
@endpush
@endsection

