@extends('layouts.app')

@section('title', 'Trang chủ - Techno1')

@section('content')
<!-- Hero Section -->
<section class="hero-section-modern">
    <div class="hero-background">
        <div class="hero-overlay"></div>
        <div class="hero-pattern"></div>
    </div>
    <div class="container">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-6 hero-content">
                <div class="hero-badge fade-in-up">
                    <i class="bi bi-lightning-charge-fill me-2"></i>
                    <span>Thương hiệu điện tử uy tín</span>
                </div>
                <h1 class="hero-title fade-in-up" style="animation-delay: 0.1s">
                    Thiết bị điện tử<br>
                    <span class="text-gradient" style="color: #fde047; text-shadow: 0 0 20px rgba(253, 224, 71, 0.5), 0 2px 10px rgba(251, 191, 36, 0.4);">Chất lượng cao</span>
                </h1>
                <p class="hero-description fade-in-up" style="animation-delay: 0.2s">
                    Khám phá bộ sưu tập thiết bị điện tử đa dạng với giá cả hợp lý. 
                    Cam kết chất lượng và dịch vụ chăm sóc khách hàng tốt nhất.
                </p>
                <div class="hero-buttons fade-in-up" style="animation-delay: 0.3s">
                    <a href="{{ route('products.index') }}" class="btn btn-hero-primary">
                        <i class="bi bi-grid me-2"></i> Xem sản phẩm
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <a href="#features" class="btn btn-hero-outline">
                        <i class="bi bi-info-circle me-2"></i> Tìm hiểu thêm
                    </a>
                </div>
                <div class="hero-stats fade-in-up" style="animation-delay: 0.4s">
                    <div class="stat-item">
                        <div class="stat-number">1000+</div>
                        <div class="stat-label">Sản phẩm</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">5000+</div>
                        <div class="stat-label">Khách hàng</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">98%</div>
                        <div class="stat-label">Hài lòng</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 hero-image fade-in" style="animation-delay: 0.5s">
                <div class="hero-image-wrapper">
                    <div class="floating-shapes">
                        <div class="shape shape-1"></div>
                        <div class="shape shape-2"></div>
                        <div class="shape shape-3"></div>
                    </div>
                    <div class="hero-main-image">
                        <i class="bi bi-device-ssd-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="features-section">
    <div class="features-background">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Tại sao chọn Techno1?</h2>
                <p class="section-subtitle">Những lý do khiến khách hàng tin tưởng chúng tôi</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4 class="feature-title">Chất lượng đảm bảo</h4>
                        <p class="feature-description">Sản phẩm chính hãng, đầy đủ bảo hành và chứng từ</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-truck"></i>
                        </div>
                        <h4 class="feature-title">Giao hàng nhanh</h4>
                        <p class="feature-description">Miễn phí vận chuyển toàn quốc, giao hàng trong 24h</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <h4 class="feature-title">Đổi trả dễ dàng</h4>
                        <p class="feature-description">Chính sách đổi trả linh hoạt trong 7 ngày đầu</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-headset"></i>
                        </div>
                        <h4 class="feature-title">Hỗ trợ 24/7</h4>
                        <p class="feature-description">Đội ngũ tư vấn chuyên nghiệp, sẵn sàng hỗ trợ</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
@if($categories->count() > 0)
<section class="categories-section">
    <div class="categories-background">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Danh mục sản phẩm</h2>
                <p class="section-subtitle">Khám phá các danh mục sản phẩm phong phú</p>
            </div>
            <div class="row g-4">
                @foreach($categories->take(6) as $category)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('products.index', ['category' => $category->id]) }}" class="category-card">
                        <div class="category-icon">
                            <i class="bi bi-grid-3x3-gap"></i>
                        </div>
                        <h4 class="category-name">{{ $category->name }}</h4>
                        <p class="category-count">{{ $category->products_count ?? 0 }} sản phẩm</p>
                        <div class="category-arrow">
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<!-- Featured Products Section -->
@if($featuredProducts->count() > 0)
<section class="products-section">
    <div class="products-background">
        <div class="container">
        <div class="section-header d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="section-title mb-2">
            <i class="bi bi-star-fill text-warning me-2"></i>Sản phẩm nổi bật
        </h2>
                <p class="section-subtitle mb-0">Những sản phẩm được yêu thích nhất</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-view-all d-none d-md-flex">
                Xem tất cả <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
    <div class="row g-4">
        @foreach($featuredProducts as $index => $product)
        <div class="col-md-6 col-lg-3">
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
                            <i class="bi bi-tag me-1"></i>{{ $product->category->name }}
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
    </div>
</section>
@endif

<!-- Latest Products Section -->
@if($latestProducts->count() > 0)
<section class="products-section products-section-alt">
    <div class="products-background-alt">
        <div class="container">
        <div class="section-header d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="section-title mb-2">
            <i class="bi bi-clock-history text-info me-2"></i>Sản phẩm mới nhất
        </h2>
                <p class="section-subtitle mb-0">Những sản phẩm vừa được thêm vào</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-view-all d-none d-md-flex">
                Xem tất cả <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
    <div class="row g-4">
        @foreach($latestProducts as $index => $product)
        <div class="col-md-6 col-lg-3">
                <div class="product-card-modern h-100" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="product-image-wrapper">
                        <div class="product-badges">
                    @if($product->sale_price)
                            <span class="badge badge-discount">
                        -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                    </span>
                    @endif
                            <span class="badge badge-new">
                        <i class="bi bi-clock"></i> Mới
                    </span>
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
                            <i class="bi bi-tag me-1"></i>{{ $product->category->name }}
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
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="cta-section">
    <div class="cta-background">
        <div class="container">
            <div class="cta-card">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h3 class="cta-title">Bạn đang tìm kiếm sản phẩm cụ thể?</h3>
                    <p class="cta-description">Khám phá toàn bộ danh mục sản phẩm của chúng tôi và tìm thấy những gì bạn cần</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <a href="{{ route('products.index') }}" class="btn btn-cta-primary">
                        <i class="bi bi-grid me-2"></i> Xem tất cả sản phẩm
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

