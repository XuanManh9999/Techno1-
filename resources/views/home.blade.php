@extends('layouts.app')

@section('title', 'Trang chủ - Techno1')

@section('content')
<div class="hero-section text-center fade-in">
    <div class="container">
        <h1>Chào mừng đến với Techno1</h1>
        <p class="lead">Trang thiết bị điện tử chất lượng cao với giá cả hợp lý</p>
        <a href="{{ route('products.index') }}" class="btn btn-light btn-lg mt-3">
            <i class="bi bi-arrow-right"></i> Xem sản phẩm
        </a>
    </div>
</div>

@if($featuredProducts->count() > 0)
<div class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="bi bi-star-fill text-warning me-2"></i>Sản phẩm nổi bật
        </h2>
        <a href="{{ route('products.index') }}" class="btn btn-outline-primary d-none d-md-block">
            Xem tất cả <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="row g-4">
        @foreach($featuredProducts as $index => $product)
        <div class="col-md-6 col-lg-3">
            <div class="card product-card h-100 stagger-animation">
                <div class="position-relative">
                    <div class="img-zoom">
                        <img src="{{ $product->image ?? 'https://via.placeholder.com/300' }}" class="card-img-top" alt="{{ $product->name }}">
                    </div>
                    @if($product->sale_price)
                    <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                        -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                    </span>
                    @endif
                    @if($product->featured)
                    <span class="badge bg-warning position-absolute top-0 start-0 m-2">
                        <i class="bi bi-star-fill"></i> Nổi bật
                    </span>
                    @endif
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="mb-2">
                        <small class="text-muted text-uppercase">{{ $product->category->name }}</small>
                    </div>
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text text-muted small flex-grow-1">{{ \Illuminate\Support\Str::limit($product->short_description ?? '', 80) }}</p>
                    <div class="mt-auto">
                        <div class="mb-3">
                            @if($product->sale_price)
                                <div>
                                    <span class="product-price-old">{{ number_format($product->price) }}đ</span>
                                </div>
                                <span class="product-price">{{ number_format($product->sale_price) }}đ</span>
                            @else
                                <span class="product-price">{{ number_format($product->price) }}đ</span>
                            @endif
                        </div>
                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary w-100">
                            <i class="bi bi-eye me-1"></i> Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@if($latestProducts->count() > 0)
<div class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="bi bi-clock-history text-info me-2"></i>Sản phẩm mới nhất
        </h2>
        <a href="{{ route('products.index') }}" class="btn btn-outline-primary d-none d-md-block">
            Xem tất cả <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="row g-4">
        @foreach($latestProducts as $index => $product)
        <div class="col-md-6 col-lg-3">
            <div class="card product-card h-100 stagger-animation">
                <div class="position-relative">
                    <div class="img-zoom">
                        <img src="{{ $product->image ?? 'https://via.placeholder.com/300' }}" class="card-img-top" alt="{{ $product->name }}">
                    </div>
                    @if($product->sale_price)
                    <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                        -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                    </span>
                    @endif
                    <span class="badge bg-info position-absolute top-0 start-0 m-2">
                        <i class="bi bi-clock"></i> Mới
                    </span>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="mb-2">
                        <small class="text-muted text-uppercase">{{ $product->category->name }}</small>
                    </div>
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text text-muted small flex-grow-1">{{ \Illuminate\Support\Str::limit($product->short_description ?? '', 80) }}</p>
                    <div class="mt-auto">
                        <div class="mb-3">
                            @if($product->sale_price)
                                <div>
                                    <span class="product-price-old">{{ number_format($product->price) }}đ</span>
                                </div>
                                <span class="product-price">{{ number_format($product->sale_price) }}đ</span>
                            @else
                                <span class="product-price">{{ number_format($product->price) }}đ</span>
                            @endif
                        </div>
                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary w-100">
                            <i class="bi bi-eye me-1"></i> Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection

