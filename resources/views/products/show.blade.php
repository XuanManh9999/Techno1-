@extends('layouts.app')

@section('title', $product->name . ' - Techno1')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category_id]) }}">{{ $product->category->name }}</a></li>
        <li class="breadcrumb-item active">{{ $product->name }}</li>
    </ol>
</nav>

<div class="row g-4 mb-5">
    <div class="col-md-6">
        <div class="card shadow-lg border-0">
            <div class="img-zoom" style="border-radius: var(--radius);">
                <img src="{{ $product->image ?? 'https://via.placeholder.com/500' }}" 
                     class="img-fluid w-100" 
                     alt="{{ $product->name }}"
                     style="height: 500px; object-fit: cover;">
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body p-4">
                <div class="mb-3">
                    <span class="badge bg-primary mb-2">{{ $product->category->name }}</span>
                    @if($product->brand)
                        <span class="badge bg-info mb-2">{{ $product->brand->name }}</span>
                    @endif
                    @if($product->featured)
                        <span class="badge bg-warning mb-2">
                            <i class="bi bi-star-fill"></i> Sản phẩm nổi bật
                        </span>
                    @endif
                </div>

                <h1 class="mb-3 fw-bold">{{ $product->name }}</h1>
                
                <div class="mb-4">
                    <p class="text-muted mb-2">
                        <i class="bi bi-upc-scan me-1"></i>SKU: <code>{{ $product->sku }}</code>
                    </p>
                    @if($product->sale_price)
                        <div class="d-flex align-items-baseline gap-3 mb-2">
                            <span class="product-price-old fs-5">{{ number_format($product->price) }}đ</span>
                            <span class="product-price fs-2">{{ number_format($product->sale_price) }}đ</span>
                            <span class="badge bg-danger fs-6">
                                Tiết kiệm {{ number_format($product->price - $product->sale_price) }}đ
                            </span>
                        </div>
                    @else
                        <span class="product-price fs-2">{{ number_format($product->price) }}đ</span>
                    @endif
                </div>

                <div class="card bg-light mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-success me-2 fs-5"></i>
                                    <div>
                                        <small class="text-muted d-block">Tình trạng</small>
                                        <strong class="d-block">
                                            @if($product->isInStock())
                                                <span class="text-success">Còn hàng</span>
                                            @else
                                                <span class="text-danger">Hết hàng</span>
                                            @endif
                                        </strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-box-seam text-primary me-2 fs-5"></i>
                                    <div>
                                        <small class="text-muted d-block">Tồn kho</small>
                                        <strong class="d-block">{{ $product->stock_quantity }} sản phẩm</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($product->short_description)
                <div class="mb-4">
                    <h5 class="mb-3">
                        <i class="bi bi-info-circle me-2"></i>Mô tả ngắn
                    </h5>
                    <p class="text-muted">{{ $product->short_description }}</p>
                </div>
                @endif

                @auth
                    @if($product->isInStock())
                    <form action="{{ route('cart.store') }}" method="POST" class="mb-4" id="addToCartForm">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="variant_id" id="selected_variant_id" value="">

                        @if($product->hasVariants())
                        <!-- Variant Selection -->
                        <div class="mb-4">
                            @php
                                $variantGroups = [];
                                foreach ($product->variants as $variant) {
                                    if ($variant->attributes) {
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
                                       max="{{ $product->stock_quantity }}" 
                                       readonly>
                                <button class="quantity-btn quantity-btn-plus" type="button" onclick="increaseQuantity()" id="increaseBtn">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle me-1"></i>
                                <span id="stockInfo">Tối đa: {{ $product->stock_quantity }} sản phẩm</span>
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

@if($product->description)
<div class="card shadow-sm border-0 mb-5">
    <div class="card-header bg-white">
        <h4 class="mb-0">
            <i class="bi bi-file-text me-2"></i>Mô tả chi tiết
        </h4>
    </div>
    <div class="card-body">
        <div class="product-description">
            {!! nl2br(e($product->description)) !!}
        </div>
    </div>
</div>
@endif

@if($relatedProducts->count() > 0)
<div class="mb-5">
    <h3 class="mb-4">
        <i class="bi bi-grid-3x3-gap me-2"></i>Sản phẩm liên quan
    </h3>
    <div class="row g-4">
        @foreach($relatedProducts as $relatedProduct)
        <div class="col-md-6 col-lg-3">
            <div class="card product-card h-100 stagger-animation">
                <div class="position-relative">
                    <div class="img-zoom">
                        <img src="{{ $relatedProduct->image ?? 'https://via.placeholder.com/300' }}" 
                             class="card-img-top" 
                             alt="{{ $relatedProduct->name }}">
                    </div>
                    @if($relatedProduct->sale_price)
                    <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                        -{{ round((($relatedProduct->price - $relatedProduct->sale_price) / $relatedProduct->price) * 100) }}%
                    </span>
                    @endif
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                    <div class="mt-auto">
                        <div class="mb-3">
                            @if($relatedProduct->sale_price)
                                <div class="mb-1">
                                    <span class="product-price-old">{{ number_format($relatedProduct->price) }}đ</span>
                                </div>
                                <span class="product-price">{{ number_format($relatedProduct->sale_price) }}đ</span>
                            @else
                                <span class="product-price">{{ number_format($relatedProduct->price) }}đ</span>
                            @endif
                        </div>
                        <a href="{{ route('products.show', $relatedProduct->slug) }}" class="btn btn-primary w-100">
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
