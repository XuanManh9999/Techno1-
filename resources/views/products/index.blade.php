@extends('layouts.app')

@section('title', 'Sản phẩm - Techno1')

@section('content')
<div class="row g-4">
    <div class="col-lg-3 col-md-4">
        <div class="card filter-sidebar shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-funnel-fill me-2"></i>Bộ lọc
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('products.index') }}">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-search me-1"></i>Tìm kiếm
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" 
                                   value="{{ request('search') }}" 
                                   placeholder="Tên sản phẩm...">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-tags me-1"></i>Danh mục
                        </label>
                        <select name="category" class="form-select">
                            <option value="">Tất cả danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-award me-1"></i>Thương hiệu
                        </label>
                        <select name="brand" class="form-select">
                            <option value="">Tất cả thương hiệu</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-currency-dollar me-1"></i>Khoảng giá
                        </label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" name="price_min" class="form-control" 
                                       value="{{ request('price_min') }}" 
                                       placeholder="Từ">
                            </div>
                            <div class="col-6">
                                <input type="number" name="price_max" class="form-control" 
                                       value="{{ request('price_max') }}" 
                                       placeholder="Đến">
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel-fill me-1"></i>Áp dụng bộ lọc
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Xóa bộ lọc
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-9 col-md-8">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <div>
                <h2 class="mb-1">Danh sách sản phẩm</h2>
                <p class="text-muted mb-0">
                    <i class="bi bi-box-seam me-1"></i>
                    Tìm thấy <strong>{{ $products->total() }}</strong> sản phẩm
                </p>
            </div>
        </div>
        
        @if($products->count() > 0)
        <div class="row g-4">
            @foreach($products as $index => $product)
            <div class="col-md-6 col-lg-4">
                <div class="card product-card h-100 stagger-animation">
                    <div class="position-relative">
                        <div class="img-zoom">
                            <img src="{{ $product->image ?? 'https://via.placeholder.com/300' }}" 
                                 class="card-img-top" 
                                 alt="{{ $product->name }}"
                                 loading="lazy">
                        </div>
                        @if($product->sale_price)
                        <span class="badge bg-danger position-absolute top-0 end-0 m-2 shadow">
                            -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                        </span>
                        @endif
                        @if($product->featured)
                        <span class="badge bg-warning position-absolute top-0 start-0 m-2 shadow">
                            <i class="bi bi-star-fill"></i> Nổi bật
                        </span>
                        @endif
                        @if(!$product->isInStock())
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <span class="badge bg-dark fs-6">Hết hàng</span>
                        </div>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <small class="text-muted text-uppercase fw-semibold">
                                <i class="bi bi-tag me-1"></i>{{ $product->category->name }}
                            </small>
                        </div>
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-muted small flex-grow-1">
                            {{ \Illuminate\Support\Str::limit($product->short_description ?? '', 80) }}
                        </p>
                        <div class="mt-auto">
                            <div class="mb-3">
                                @if($product->sale_price)
                                    <div class="mb-1">
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

        <div class="mt-4">
            {{ $products->links() }}
        </div>
        @else
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox display-1 text-muted d-block mb-3"></i>
                <h4 class="text-muted mb-2">Không tìm thấy sản phẩm nào</h4>
                <p class="text-muted mb-4">Hãy thử điều chỉnh bộ lọc của bạn</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Xem tất cả sản phẩm
                </a>
            </div>
        </div>
        @endif

        @if($products->hasPages())
        <div class="mt-5">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

