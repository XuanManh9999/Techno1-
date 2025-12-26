@extends('layouts.app')

@section('title', 'Thanh toán - Techno1')

@section('content')
<!-- Payment Section -->
<section class="payment-section">
    <div class="payment-background">
        <div class="container">
            <!-- Breadcrumb -->
            <div class="breadcrumb-section mb-4">
                <div class="breadcrumb-background">
                    <div class="container">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb-modern">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Đơn hàng</a></li>
                                <li class="breadcrumb-item active">Thanh toán</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <!-- Order Information Card -->
                    <div class="payment-order-info-card">
                        <div class="payment-order-info-header">
                            <h5 class="payment-order-info-title">
                                <i class="bi bi-receipt me-2"></i>Thông tin đơn hàng
                            </h5>
                        </div>
                        <div class="payment-order-info-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="payment-info-item">
                                        <div class="payment-info-label">
                                            <i class="bi bi-hash me-1"></i>Mã đơn hàng
                                        </div>
                                        <div class="payment-info-value">{{ $order->order_number }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="payment-info-item">
                                        <div class="payment-info-label">
                                            <i class="bi bi-currency-dollar me-1"></i>Tổng tiền
                                        </div>
                                        <div class="payment-info-value payment-info-value-total">{{ number_format($order->total_amount) }}₫</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="payment-info-item">
                                        <div class="payment-info-label">
                                            <i class="bi bi-credit-card me-1"></i>Phương thức thanh toán
                                        </div>
                                        <div class="payment-info-value">
                                            @if($order->payment_method === 'VNPAY')
                                                <span class="payment-method-badge payment-method-vnpay">
                                                    <i class="bi bi-credit-card-2-front me-1"></i>VNPAY
                                                </span>
                                            @elseif($order->payment_method === 'COD')
                                                <span class="payment-method-badge payment-method-cod">
                                                    <i class="bi bi-cash-coin me-1"></i>Thanh toán khi nhận hàng
                                                </span>
                                            @else
                                                <span class="text-muted">Chưa chọn</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="payment-info-item">
                                        <div class="payment-info-label">
                                            <i class="bi bi-info-circle me-1"></i>Trạng thái
                                        </div>
                                        <div class="payment-info-value">
                                            <span class="payment-status-badge payment-status-pending">
                                                <i class="bi bi-clock me-1"></i>Chờ thanh toán
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($order->payment_method === 'VNPAY')
                    <!-- VNPAY Payment Card -->
                    <div class="payment-vnpay-card">
                        <div class="payment-vnpay-header">
                            <h5 class="payment-vnpay-title">
                                <i class="bi bi-credit-card me-2"></i>Thanh toán qua VNPAY
                            </h5>
                        </div>
                        <div class="payment-vnpay-body">
                            <div class="payment-instruction-box">
                                <div class="payment-instruction-icon">
                                    <i class="bi bi-info-circle"></i>
                                </div>
                                <div class="payment-instruction-content">
                                    <strong>Hướng dẫn:</strong> Bạn sẽ được chuyển đến cổng thanh toán VNPAY để hoàn tất thanh toán. 
                                    Sau khi thanh toán thành công, bạn sẽ được chuyển về trang đơn hàng.
                                    <br><br>
                                    <small class="text-muted">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        <strong>Lưu ý:</strong> Nếu gặp lỗi "Website chưa được phê duyệt", vui lòng đăng ký website của bạn trong VNPAY Sandbox hoặc liên hệ VNPAY để được hỗ trợ.
                                    </small>
                                </div>
                            </div>

                            <form action="{{ route('payment.vnpay', $order->id) }}" method="POST" id="vnpayForm">
                                @csrf
                                <div class="payment-vnpay-actions">
                                    <button type="submit" class="btn-payment-vnpay">
                                        <i class="bi bi-credit-card-2-front me-2"></i>Thanh toán qua VNPAY
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @else
                    <!-- COD Confirmation Card -->
                    <div class="payment-cod-card">
                        <div class="payment-cod-content">
                            <div class="payment-cod-icon">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <h4 class="payment-cod-title">Đơn hàng đã được xác nhận!</h4>
                            <p class="payment-cod-text">
                                Bạn đã chọn phương thức thanh toán khi nhận hàng. 
                                Vui lòng chuẩn bị số tiền <strong class="payment-cod-amount">{{ number_format($order->total_amount) }}₫</strong> 
                                để thanh toán khi nhận hàng.
                            </p>
                            <a href="{{ route('orders.show', $order->id) }}" class="btn-view-order">
                                <i class="bi bi-eye me-2"></i>Xem chi tiết đơn hàng
                            </a>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Shipping Information Sidebar -->
                <div class="col-lg-4">
                    <div class="payment-shipping-card">
                        <div class="payment-shipping-header">
                            <h5 class="payment-shipping-title">
                                <i class="bi bi-truck me-2"></i>Thông tin giao hàng
                            </h5>
                        </div>
                        <div class="payment-shipping-body">
                            <div class="shipping-info-item">
                                <div class="shipping-info-label">
                                    <i class="bi bi-person me-1"></i>Người nhận
                                </div>
                                <div class="shipping-info-value">{{ $order->shipping_name }}</div>
                            </div>
                            <div class="shipping-info-item">
                                <div class="shipping-info-label">
                                    <i class="bi bi-telephone me-1"></i>Điện thoại
                                </div>
                                <div class="shipping-info-value">{{ $order->shipping_phone }}</div>
                            </div>
                            <div class="shipping-info-item">
                                <div class="shipping-info-label">
                                    <i class="bi bi-geo-alt me-1"></i>Địa chỉ
                                </div>
                                <div class="shipping-info-value">{{ $order->shipping_address }}</div>
                            </div>
                            @if($order->notes)
                            <div class="shipping-info-item">
                                <div class="shipping-info-label">
                                    <i class="bi bi-sticky me-1"></i>Ghi chú
                                </div>
                                <div class="shipping-info-value">{{ $order->notes }}</div>
                            </div>
                            @endif
                            <div class="payment-shipping-divider"></div>
                            <div class="shipping-total-row">
                                <span class="shipping-total-label">Tổng tiền:</span>
                                <span class="shipping-total-value">{{ number_format($order->total_amount) }}₫</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const vnpayForm = document.getElementById('vnpayForm');
    if (vnpayForm) {
        vnpayForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang chuyển hướng...';
            }
        });
    }
});
</script>
@endpush
@endsection
