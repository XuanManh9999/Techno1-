@extends('layouts.app')

@section('title', 'Giỏ hàng - Techno1')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h2 class="mb-0">
        <i class="bi bi-cart3 me-2"></i>Giỏ hàng của tôi
    </h2>
</div>

@if($cartItems->count() > 0)
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0 d-flex align-items-center justify-content-between">
                    <span>
                        <i class="bi bi-bag me-2"></i>Sản phẩm trong giỏ hàng
                    </span>
                    <span class="badge bg-primary rounded-pill">{{ $cartItems->count() }}</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th style="width: 45%;">Sản phẩm</th>
                                <th class="text-center" style="width: 15%;">Giá</th>
                                <th class="text-center" style="width: 20%;">Số lượng</th>
                                <th class="text-center" style="width: 15%;">Thành tiền</th>
                                <th class="text-center" style="width: 5%;">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="img-zoom me-3" style="width: 90px; height: 90px; border-radius: var(--radius-sm); overflow: hidden;">
                                            <img src="{{ $item->product_image ?? $item->product->image ?? 'https://via.placeholder.com/120' }}" 
                                                 alt="{{ $item->product_name }}" 
                                                 class="w-100 h-100" 
                                                 style="object-fit: cover;">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold text-truncate">
                                                {{ $item->product_name }}
                                            </h6>
                                            @if($item->variant)
                                            <small class="text-info d-block mb-1">
                                                <i class="bi bi-tag me-1"></i>{{ $item->variant->attributes_string }}
                                            </small>
                                            @endif
                                            <small class="text-muted d-block mb-1">
                                                <i class="bi bi-upc-scan me-1"></i>SKU: {{ $item->variant ? $item->variant->sku : $item->product->sku }}
                                            </small>
                                            <small class="text-danger fw-semibold">
                                                {{ number_format($item->product_price) }}đ
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <strong class="text-danger">{{ number_format($item->product->final_price) }}đ</strong>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('PUT')
                                        <div class="quantity-selector quantity-selector-sm">
                                            <button class="quantity-btn quantity-btn-minus" 
                                                    type="button" 
                                                    onclick="this.parentElement.querySelector('input').stepDown(); this.form.submit();">
                                                <i class="bi bi-dash-lg"></i>
                                            </button>
                                            <input type="number" 
                                                   name="quantity" 
                                                   value="{{ $item->quantity }}" 
                                                   min="1" 
                                                   max="{{ $item->product->stock_quantity }}" 
                                                   class="quantity-input" 
                                                   onchange="this.form.submit()">
                                            <button class="quantity-btn quantity-btn-plus" 
                                                    type="button" 
                                                    onclick="this.parentElement.querySelector('input').stepUp(); this.form.submit();">
                                                <i class="bi bi-plus-lg"></i>
                                            </button>
                                        </div>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <strong class="text-danger fs-5">{{ number_format($item->subtotal) }}đ</strong>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-outline-danger btn-sm btn-icon-only" 
                                                onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');"
                                                title="Xóa sản phẩm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-lg border-0 sticky-top order-summary-card" style="top: 100px;">
            <div class="card-header bg-gradient text-white">
                <h5 class="mb-0">
                    <i class="bi bi-receipt me-2"></i>Tổng kết đơn hàng
                </h5>
            </div>
            <div class="card-body">
                <div class="summary-item">
                    <span class="summary-label">Tạm tính:</span>
                    <span class="summary-value">{{ number_format($total) }}đ</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Phí vận chuyển:</span>
                    <span class="summary-value text-success">
                        <i class="bi bi-check-circle me-1"></i>Miễn phí
                    </span>
                </div>
                <hr class="my-3">
                <div class="summary-item summary-total">
                    <span class="summary-label">Tổng cộng:</span>
                    <span class="summary-value text-danger">{{ number_format($total) }}đ</span>
                </div>
                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('cart.checkout') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-credit-card me-2"></i>Thanh toán ngay
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="card shadow-sm border-0">
    <div class="card-body text-center py-5">
        <i class="bi bi-cart-x display-1 text-muted d-block mb-3"></i>
        <h3 class="mb-3">Giỏ hàng trống</h3>
        <p class="text-muted mb-4">Bạn chưa có sản phẩm nào trong giỏ hàng. Hãy bắt đầu mua sắm!</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-arrow-right me-2"></i>Tiếp tục mua sắm
        </a>
    </div>
</div>
@endif
@endsection
