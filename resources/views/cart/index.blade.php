@extends('layouts.app')

@section('title', 'Giỏ hàng - Techno1')

@section('content')
<!-- Cart Section -->
<section class="cart-section">
    <div class="cart-background">
        <div class="container">
            <div class="cart-header mb-5">
                <a href="{{ route('products.index') }}" class="btn-back">
                    <i class="bi bi-arrow-left me-2"></i>Tiếp tục mua sắm
                </a>
                <div class="cart-title-wrapper">
                    <h2 class="section-title">
                        <i class="bi bi-cart3 me-2"></i>Giỏ hàng của tôi
                    </h2>
                    @if($cartItems->count() > 0)
                    <p class="section-subtitle mb-0">
                        Bạn có <strong>{{ $cartItems->count() }}</strong> sản phẩm trong giỏ hàng
                    </p>
                    @endif
                </div>
            </div>

@if($cartItems->count() > 0)
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="cart-items-card">
                        <div class="cart-items-header">
                            <h5 class="cart-items-title">
                                <i class="bi bi-bag me-2"></i>Sản phẩm trong giỏ hàng
                            </h5>
                            <span class="cart-items-badge">{{ $cartItems->count() }} sản phẩm</span>
                        </div>
                        <div class="cart-items-body">
                            @foreach($cartItems as $item)
                            <div class="cart-item-modern">
                                <div class="cart-item-image-wrapper">
                                    <a href="{{ route('products.show', $item->product->slug) }}">
                                        @if($item->product_image ?? $item->product->image)
                                            <img src="{{ $item->product_image ?? $item->product->image }}" 
                                                 alt="{{ $item->product_name }}" 
                                                 class="cart-item-image"
                                                 onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'120\' height=\'120\'%3E%3Crect width=\'120\' height=\'120\' fill=\'%23f1f5f9\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%2364748b\' font-family=\'Arial\' font-size=\'12\'%3ENo Image%3C/text%3E%3C/svg%3E';">
                                        @else
                                            <div class="cart-item-image-placeholder">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                    </a>
                                </div>
                                <div class="cart-item-info">
                                    <div class="cart-item-name-wrapper">
                                        <h6 class="cart-item-name">
                                            <a href="{{ route('products.show', $item->product->slug) }}">{{ $item->product_name }}</a>
                                        </h6>
                                        @if($item->variant)
                                        <div class="cart-item-variant">
                                            <i class="bi bi-tag me-1"></i>{{ $item->variant->attributes_string }}
                                        </div>
                                        @endif
                                        <div class="cart-item-sku">
                                            <i class="bi bi-upc-scan me-1"></i>SKU: {{ $item->variant ? $item->variant->sku : $item->product->sku }}
                                        </div>
                                    </div>
                                </div>
                                <div class="cart-item-price">
                                    <div class="price-label">Đơn giá</div>
                                    <div class="price-value">{{ number_format($item->product->final_price) }}₫</div>
                                </div>
                                <div class="cart-item-quantity">
                                    <div class="quantity-label">Số lượng</div>
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="quantity-form">
                                        @csrf
                                        @method('PUT')
                                        <div class="quantity-selector quantity-selector-sm">
                                            <button class="quantity-btn quantity-btn-minus" 
                                                    type="button" 
                                                    onclick="updateQuantity(this, -1)">
                                                <i class="bi bi-dash-lg"></i>
                                            </button>
                                            <input type="number" 
                                                   name="quantity" 
                                                   value="{{ $item->quantity }}" 
                                                   min="1" 
                                                   max="{{ $item->product->stock_quantity ?? 999 }}" 
                                                   class="quantity-input" 
                                                   onchange="this.form.submit()"
                                                   readonly>
                                            <button class="quantity-btn quantity-btn-plus" 
                                                    type="button" 
                                                    onclick="updateQuantity(this, 1)">
                                                <i class="bi bi-plus-lg"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="cart-item-subtotal">
                                    <div class="subtotal-label">Thành tiền</div>
                                    <div class="subtotal-value">{{ number_format($item->subtotal) }}₫</div>
                                </div>
                                <div class="cart-item-actions">
                                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn-delete-item" 
                                                onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?');"
                                                title="Xóa sản phẩm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="order-summary-card-modern">
                        <div class="order-summary-header">
                            <h5 class="order-summary-title">
                                <i class="bi bi-receipt me-2"></i>Tổng kết đơn hàng
                            </h5>
                        </div>
                        <div class="order-summary-body">
                            <div class="summary-row">
                                <span class="summary-label">Tạm tính:</span>
                                <span class="summary-value">{{ number_format($total) }}₫</span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Phí vận chuyển:</span>
                                <span class="summary-value summary-value-success">
                                    <i class="bi bi-check-circle me-1"></i>Miễn phí
                                </span>
                            </div>
                            <div class="summary-divider"></div>
                            <div class="summary-row summary-row-total">
                                <span class="summary-label">Tổng cộng:</span>
                                <span class="summary-value summary-value-total">{{ number_format($total) }}₫</span>
                            </div>
                            <div class="order-summary-actions">
                                <a href="{{ route('cart.checkout') }}" class="btn-checkout">
                                    <i class="bi bi-credit-card me-2"></i>Thanh toán ngay
                                </a>
                                <a href="{{ route('products.index') }}" class="btn-continue-shopping">
                                    <i class="bi bi-arrow-left me-2"></i>Tiếp tục mua sắm
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@else
            <div class="empty-cart-card">
                <div class="empty-cart-content">
                    <div class="empty-cart-icon">
                        <i class="bi bi-cart-x"></i>
                    </div>
                    <h3 class="empty-cart-title">Giỏ hàng trống</h3>
                    <p class="empty-cart-text">Bạn chưa có sản phẩm nào trong giỏ hàng. Hãy bắt đầu mua sắm ngay!</p>
                    <a href="{{ route('products.index') }}" class="btn-shopping-now">
                        <i class="bi bi-arrow-right me-2"></i>Tiếp tục mua sắm
                    </a>
                </div>
            </div>
@endif
        </div>
    </div>
</section>

@push('scripts')
<script>
    function updateQuantity(button, change) {
        const form = button.closest('.quantity-form');
        const input = form.querySelector('.quantity-input');
        const currentValue = parseInt(input.value);
        const min = parseInt(input.getAttribute('min'));
        const max = parseInt(input.getAttribute('max'));
        const newValue = currentValue + change;
        
        if (newValue >= min && newValue <= max) {
            input.value = newValue;
            form.submit();
        }
    }
</script>
@endpush
@endsection

