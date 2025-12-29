@extends('layouts.app')

@section('title', 'Sản phẩm - Techno1')

@section('content')
<!-- Products Page Section -->
<section class="products-page-section">
    <div class="products-page-background">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-md-4">
                    <div class="filter-sidebar-card">
                        <div class="filter-header">
                            <h5 class="filter-title">
                                <i class="bi bi-funnel-fill me-2"></i>Bộ lọc
                            </h5>
                        </div>
                        <div class="filter-body">
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
                                <option value="{{ $category->id }}" {{ request('category') == $category->id || request('category') == (string)$category->id ? 'selected' : '' }}>
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
                                <option value="{{ $brand->id }}" {{ request('brand') == $brand->id || request('brand') == (string)$brand->id ? 'selected' : '' }}>
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
                    <div class="products-header mb-5">
                        <div>
                            <h2 class="section-title mb-2">Danh sách sản phẩm</h2>
                            <p class="section-subtitle mb-0">
                                <i class="bi bi-box-seam me-1"></i>
                                Tìm thấy <strong>{{ $products->total() }}</strong> sản phẩm
                            </p>
                        </div>
                    </div>
                    
                    @if($products->count() > 0)
                    <div class="row g-4">
                        @foreach($products as $index => $product)
                        <div class="col-md-6 col-lg-4">
                            <div class="product-card-modern h-100" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                                <div class="product-image-wrapper">
                                    <div class="product-badges">
                                        @if($product->sale_price)
                                        <span class="badge badge-discount">
                                            -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                                        </span>
                                        @endif
                                        @if($product->featured)
                                        <span class="badge badge-featured">
                                            <i class="bi bi-star-fill"></i> Nổi bật
                                        </span>
                                        @endif
                                        @if(!$product->isInStock())
                                        <span class="badge badge-out-of-stock">
                                            Hết hàng
                                        </span>
                                        @endif
                                    </div>
                                    <a href="{{ route('products.show', $product->slug) }}" class="product-image-link">
                                        @if($product->image)
                                            <img src="{{ $product->image }}" 
                                                 class="product-image" 
                                                 alt="{{ $product->name }}"
                                                 loading="lazy"
                                                 onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'300\' height=\'300\'%3E%3Crect width=\'300\' height=\'300\' fill=\'%23f1f5f9\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%2364748b\' font-family=\'Arial\' font-size=\'14\'%3ENo Image%3C/text%3E%3C/svg%3E';">
                                        @else
                                            <div class="product-image-placeholder">
                                                <i class="bi bi-image"></i>
                                                <span>Không có hình ảnh</span>
                                            </div>
                                        @endif
                                    </a>
                                    <div class="product-overlay">
                                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-quick-view">
                                            <i class="bi bi-eye"></i> Xem nhanh
                                        </a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <div class="product-category">
                                        <i class="bi bi-tag me-1"></i>{{ $product->category->name ?? 'N/A' }}
                                    </div>
                                    <h5 class="product-name">
                                        <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                    </h5>
                                    @if($product->short_description)
                                    <p class="product-description">{{ \Illuminate\Support\Str::limit($product->short_description, 70) }}</p>
                                    @endif
                                    <div class="product-footer">
                                        <div class="product-price-wrapper">
                                            @if($product->sale_price)
                                                <span class="product-price-old">{{ number_format($product->price) }}₫</span>
                                                <span class="product-price">{{ number_format($product->sale_price) }}₫</span>
                                            @else
                                                <span class="product-price">{{ number_format($product->price) }}₫</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-product-action" title="Xem chi tiết">
                                            <i class="bi bi-cart-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-5">
                        {{ $products->links('vendor.pagination.custom') }}
                    </div>
                    @else
                    <div class="empty-state-card">
                        <div class="empty-state-content">
                            <i class="bi bi-inbox empty-state-icon"></i>
                            <h4 class="empty-state-title">Không tìm thấy sản phẩm nào</h4>
                            <p class="empty-state-text">Hãy thử điều chỉnh bộ lọc của bạn</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Xem tất cả sản phẩm
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

