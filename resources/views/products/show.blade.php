@extends('layouts.app')

@section('title', $product->name . ' - Techno1')

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
                        <a href="{{ route('products.index') }}">Sản phẩm</a>
                    </li>
                    @if($product->category)
                    <li class="breadcrumb-item">
                        <a href="{{ route('products.index', ['category' => $product->category_id]) }}">{{ $product->category->name }}</a>
                    </li>
                    @endif
                    <li class="breadcrumb-item active">{{ \Illuminate\Support\Str::limit($product->name, 30) }}</li>
                </ol>
            </nav>
        </div>
    </div>
</section>

<!-- Product Detail Section -->
<section class="product-detail-section">
    <div class="product-detail-background">
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <div class="product-image-card">
                        <div class="product-image-main">
                            @if($product->image)
                                <img src="{{ $product->image }}" 
                                     class="product-main-image" 
                                     alt="{{ $product->name }}"
                                     id="mainProductImage"
                                     onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'500\' height=\'500\'%3E%3Crect width=\'500\' height=\'500\' fill=\'%23f1f5f9\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%2364748b\' font-family=\'Arial\' font-size=\'16\'%3ENo Image%3C/text%3E%3C/svg%3E';">
                            @else
                                <div class="product-image-placeholder-large">
                                    <i class="bi bi-image"></i>
                                    <span>Không có hình ảnh</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="product-info-card">
                        <div class="product-badges-top mb-3">
                            @if($product->category)
                                <span class="badge badge-category">
                                    <i class="bi bi-tag me-1"></i>{{ $product->category->name }}
                                </span>
                            @endif
                            @if($product->brand)
                                <span class="badge badge-brand">
                                    <i class="bi bi-award me-1"></i>{{ $product->brand->name }}
                                </span>
                            @endif
                            @if($product->featured)
                                <span class="badge badge-featured">
                                    <i class="bi bi-star-fill"></i> Nổi bật
                                </span>
                            @endif
                            @if($product->sale_price)
                                <span class="badge badge-discount">
                                    -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                                </span>
                            @endif
                        </div>

                        <h1 class="product-title mb-3">{{ $product->name }}</h1>
                        
                        <div class="product-sku mb-4">
                            <span class="text-muted">
                                <i class="bi bi-upc-scan me-1"></i>SKU: 
                            </span>
                            <code class="sku-code">{{ $product->sku }}</code>
                        </div>

                        <div class="product-pricing mb-4">
                            @if($product->sale_price)
                                <div class="price-wrapper">
                                    <span class="product-price-old">{{ number_format($product->price) }}₫</span>
                                    <span class="product-price-current">{{ number_format($product->sale_price) }}₫</span>
                                </div>
                                <div class="savings-badge">
                                    <i class="bi bi-piggy-bank me-1"></i>
                                    Tiết kiệm {{ number_format($product->price - $product->sale_price) }}₫
                                </div>
                            @else
                                <span class="product-price-current">{{ number_format($product->price) }}₫</span>
                            @endif
                        </div>

                        <div class="product-status-card mb-4">
                            <div class="status-item">
                                <div class="status-icon status-icon-success">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div class="status-content">
                                    <small class="status-label">Tình trạng</small>
                                    <strong class="status-value">
                                        @if($product->isInStock())
                                            <span class="text-success">Còn hàng</span>
                                        @else
                                            <span class="text-danger">Hết hàng</span>
                                        @endif
                                    </strong>
                                </div>
                            </div>
                            <div class="status-item">
                                <div class="status-icon status-icon-primary">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                                <div class="status-content">
                                    <small class="status-label">Tồn kho</small>
                                    <strong class="status-value">{{ $product->stock_quantity ?? 0 }} sản phẩm</strong>
                                </div>
                            </div>
                        </div>

                        @if($product->short_description)
                        <div class="product-short-description mb-4">
                            <h5 class="description-title">
                                <i class="bi bi-info-circle me-2"></i>Mô tả ngắn
                            </h5>
                            <p class="description-text">{{ $product->short_description }}</p>
                        </div>
                        @endif

                @auth
                    @if($product->isInStock())
                    <form action="{{ route('cart.store') }}" method="POST" class="mb-4" id="addToCartForm">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="variant_id" id="selected_variant_id" value="">

                        @if($product->hasVariants() && $product->variants && $product->variants->count() > 0)
                        <!-- Variant Selection -->
                        <div class="mb-4">
                            @php
                                $variantGroups = [];
                                foreach ($product->variants as $variant) {
                                    if ($variant && $variant->attributes && is_array($variant->attributes)) {
                                        foreach ($variant->attributes as $attrName => $attrValue) {
                                            if (!isset($variantGroups[$attrName])) {
                                                $variantGroups[$attrName] = [];
                                            }
                                            if (!in_array($attrValue, $variantGroups[$attrName])) {
                                                $variantGroups[$attrName][] = $attrValue;
                                            }
                                        }
                                    }
                                }
                            @endphp

                            @foreach($variantGroups as $attrName => $attrValues)
                            <div class="mb-3">
                                <label class="form-label fw-semibold mb-2">
                                    <i class="bi bi-tag me-1"></i>{{ $attrName }} *
                                </label>
                                <div class="variant-options d-flex flex-wrap gap-2">
                                    @foreach($attrValues as $attrValue)
                                    <button type="button" 
                                            class="variant-option-btn btn btn-outline-secondary"
                                            data-attribute="{{ $attrName }}"
                                            data-value="{{ $attrValue }}"
                                            onclick="selectVariantOption('{{ $attrName }}', '{{ $attrValue }}', this)">
                                        {{ $attrValue }}
                                    </button>
                                    @endforeach
                                </div>
                                <input type="hidden" name="selected_attributes[{{ $attrName }}]" id="attr_{{ str_replace(' ', '_', $attrName) }}" value="">
                            </div>
                            @endforeach

                            <div id="selectedVariantInfo" class="alert alert-info d-none mb-3">
                                <i class="bi bi-info-circle me-2"></i>
                                <span id="selectedVariantText"></span>
                            </div>
                        </div>
                        @endif

                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-3">
                                <i class="bi bi-123 me-1"></i>Số lượng
                            </label>
                            <div class="quantity-selector">
                                <button class="quantity-btn quantity-btn-minus" type="button" onclick="decreaseQuantity()" id="decreaseBtn">
                                    <i class="bi bi-dash-lg"></i>
                                </button>
                                <input type="number" 
                                       name="quantity" 
                                       id="quantity" 
                                       class="quantity-input" 
                                       value="1" 
                                       min="1" 
                                       max="{{ $product->stock_quantity ?? 999 }}" 
                                       readonly>
                                <button class="quantity-btn quantity-btn-plus" type="button" onclick="increaseQuantity()" id="increaseBtn">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle me-1"></i>
                                <span id="stockInfo">Tối đa: {{ $product->stock_quantity ?? 0 }} sản phẩm</span>
                            </small>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Giá:</span>
                                <strong class="text-danger fs-4" id="variantPrice">
                                    @if($product->hasVariants())
                                        {{ number_format($product->min_price) }}đ - {{ number_format($product->max_price) }}đ
                                    @else
                                        {{ number_format($product->final_price) }}đ
                                    @endif
                                </strong>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="addToCartBtn">
                                <i class="bi bi-cart-plus me-2"></i>Thêm vào giỏ hàng
                            </button>
                        </div>
                    </form>
                    @else
                    <div class="alert alert-warning mb-4">
                        <i class="bi bi-exclamation-triangle me-2"></i>Sản phẩm hiện đang hết hàng
                    </div>
                    @endif
                @else
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle me-2"></i>Vui lòng <a href="{{ route('login') }}" class="alert-link">đăng nhập</a> để mua hàng
                    </div>
                @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if($product->description)
<section class="product-description-section">
    <div class="product-description-background">
        <div class="container">
            <div class="product-description-card">
                <div class="description-header">
                    <h4 class="description-title-main">
                        <i class="bi bi-file-text me-2"></i>Mô tả chi tiết
                    </h4>
                </div>
                <div class="description-content">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@if($relatedProducts->count() > 0)
<section class="related-products-section">
    <div class="related-products-background">
        <div class="container">
            <div class="section-header mb-5">
                <h2 class="section-title">
                    <i class="bi bi-grid-3x3-gap me-2"></i>Sản phẩm liên quan
                </h2>
                <p class="section-subtitle">Những sản phẩm cùng danh mục</p>
            </div>
            <div class="row g-4">
                @foreach($relatedProducts as $index => $relatedProduct)
                <div class="col-md-6 col-lg-3">
                    <div class="product-card-modern h-100" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="product-image-wrapper">
                            <div class="product-badges">
                                @if($relatedProduct->sale_price)
                                <span class="badge badge-discount">
                                    -{{ round((($relatedProduct->price - $relatedProduct->sale_price) / $relatedProduct->price) * 100) }}%
                                </span>
                                @endif
                            </div>
                            <a href="{{ route('products.show', $relatedProduct->slug) }}" class="product-image-link">
                                @if($relatedProduct->image)
                                    <img src="{{ $relatedProduct->image }}" 
                                         class="product-image" 
                                         alt="{{ $relatedProduct->name }}"
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
                                <a href="{{ route('products.show', $relatedProduct->slug) }}" class="btn btn-quick-view">
                                    <i class="bi bi-eye"></i> Xem nhanh
                                </a>
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="product-category">
                                <i class="bi bi-tag me-1"></i>{{ $relatedProduct->category->name ?? 'N/A' }}
                            </div>
                            <h5 class="product-name">
                                <a href="{{ route('products.show', $relatedProduct->slug) }}">{{ $relatedProduct->name }}</a>
                            </h5>
                            @if($relatedProduct->short_description)
                            <p class="product-description">{{ \Illuminate\Support\Str::limit($relatedProduct->short_description, 70) }}</p>
                            @endif
                            <div class="product-footer">
                                <div class="product-price-wrapper">
                                    @if($relatedProduct->sale_price)
                                        <span class="product-price-old">{{ number_format($relatedProduct->price) }}₫</span>
                                        <span class="product-price">{{ number_format($relatedProduct->sale_price) }}₫</span>
                                    @else
                                        <span class="product-price">{{ number_format($relatedProduct->price) }}₫</span>
                                    @endif
                                </div>
                                <a href="{{ route('products.show', $relatedProduct->slug) }}" class="btn btn-product-action" title="Xem chi tiết">
                                    <i class="bi bi-cart-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

@push('scripts')
<script>
    function increaseQuantity() {
        const input = document.getElementById('quantity');
        const max = parseInt(input.getAttribute('max'));
        const current = parseInt(input.value);
        const decreaseBtn = document.getElementById('decreaseBtn');
        const increaseBtn = document.getElementById('increaseBtn');
        
        if (current < max) {
            input.value = current + 1;
            decreaseBtn.disabled = false;
        }
        
        if (parseInt(input.value) >= max) {
            increaseBtn.disabled = true;
        }
    }

    function decreaseQuantity() {
        const input = document.getElementById('quantity');
        const current = parseInt(input.value);
        const decreaseBtn = document.getElementById('decreaseBtn');
        const increaseBtn = document.getElementById('increaseBtn');
        
        if (current > 1) {
            input.value = current - 1;
            increaseBtn.disabled = false;
        }
        
        if (parseInt(input.value) <= 1) {
            decreaseBtn.disabled = true;
        }
    }

    // Initialize button states on page load
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('quantity');
        const decreaseBtn = document.getElementById('decreaseBtn');
        const increaseBtn = document.getElementById('increaseBtn');
        const max = parseInt(input.getAttribute('max'));
        const current = parseInt(input.value);
        
        if (current <= 1) {
            decreaseBtn.disabled = true;
        }
        
        if (current >= max) {
            increaseBtn.disabled = true;
        }
    });
</script>
@endpush
@endsection
