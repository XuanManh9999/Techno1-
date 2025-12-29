@extends('layouts.app')

@section('title', $post->title . ' - Blog Techno1')

@section('content')
<!-- Breadcrumb Section -->
<section class="breadcrumb-section">
    <div class="breadcrumb-background">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb-modern">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">
                            <i class="bi bi-house-door me-1"></i>Trang chủ
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('blog.index') }}">Blog</a>
                    </li>
                    <li class="breadcrumb-item active">{{ \Illuminate\Support\Str::limit($post->title, 30) }}</li>
                </ol>
            </nav>
        </div>
    </div>
</section>

<!-- Blog Post Detail Section -->
<section class="blog-detail-section py-5">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <article class="blog-post">
                    <!-- Post Header -->
                    <header class="mb-4">
                        <h1 class="display-5 fw-bold mb-3">{{ $post->title }}</h1>
                        <div class="d-flex align-items-center text-muted mb-3">
                            <span class="me-3">
                                <i class="bi bi-person-circle me-1"></i>{{ $post->user->name }}
                            </span>
                            <span class="me-3">
                                <i class="bi bi-calendar3 me-1"></i>{{ $post->published_at->format('d/m/Y H:i') }}
                            </span>
                            <span>
                                <i class="bi bi-eye me-1"></i>{{ $post->views }} lượt xem
                            </span>
                        </div>
                        @if($post->excerpt)
                            <div class="alert alert-light border-start border-primary border-3">
                                <p class="mb-0 fst-italic">{{ $post->excerpt }}</p>
                            </div>
                        @endif
                    </header>

                    <!-- Featured Image -->
                    @if($post->featured_image)
                        <div class="mb-4">
                            <img src="{{ Storage::url($post->featured_image) }}" 
                                 alt="{{ $post->title }}" 
                                 class="img-fluid rounded shadow">
                        </div>
                    @endif

                    <!-- Post Content -->
                    <div class="blog-content">
                        {!! $post->content !!}
                    </div>
                </article>

                <!-- Related Posts -->
                @if($relatedPosts->count() > 0)
                <div class="mt-5">
                    <h3 class="mb-4">Bài viết liên quan</h3>
                    <div class="row g-4">
                        @foreach($relatedPosts as $relatedPost)
                        <div class="col-md-6">
                            <div class="card shadow-sm h-100">
                                @if($relatedPost->featured_image)
                                    <a href="{{ route('blog.show', $relatedPost->slug) }}">
                                        <img src="{{ Storage::url($relatedPost->featured_image) }}" 
                                             class="card-img-top" 
                                             alt="{{ $relatedPost->title }}"
                                             style="height: 150px; object-fit: cover;">
                                    </a>
                                @endif
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <a href="{{ route('blog.show', $relatedPost->slug) }}" class="text-decoration-none">
                                            {{ $relatedPost->title }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        {{ $relatedPost->published_at->format('d/m/Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 20px;">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-info-circle me-2"></i>Thông tin bài viết
                            </h5>
                            <hr>
                            <div class="mb-3">
                                <strong>Tác giả:</strong><br>
                                {{ $post->user->name }}
                            </div>
                            <div class="mb-3">
                                <strong>Ngày đăng:</strong><br>
                                {{ $post->published_at->format('d/m/Y H:i') }}
                            </div>
                            <div class="mb-3">
                                <strong>Lượt xem:</strong><br>
                                <span class="badge bg-primary">{{ $post->views }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <a href="{{ route('blog.index') }}" class="btn btn-primary w-100">
                                <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
.blog-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
}

.blog-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1.5rem 0;
}

.blog-content p {
    margin-bottom: 1.5rem;
}

.blog-content h1,
.blog-content h2,
.blog-content h3,
.blog-content h4 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.blog-content ul,
.blog-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.blog-content blockquote {
    border-left: 4px solid var(--primary);
    padding-left: 1.5rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #666;
}

.blog-content code {
    background: #f4f4f4;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-size: 0.9em;
}

.blog-content pre {
    background: #f4f4f4;
    padding: 1rem;
    border-radius: 8px;
    overflow-x: auto;
    margin: 1.5rem 0;
}

.blog-content a {
    color: var(--primary);
    text-decoration: underline;
}

.blog-content a:hover {
    color: var(--primary-dark);
}
</style>
@endpush
@endsection

