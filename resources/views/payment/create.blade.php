@extends('layouts.app')

@section('title', 'Thanh toán - Techno1')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Đơn hàng</a></li>
        <li class="breadcrumb-item active">Thanh toán</li>
    </ol>
</nav>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-receipt me-2"></i>Thông tin đơn hàng
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-item">
                            <small class="text-muted d-block mb-1">Mã đơn hàng</small>
                            <strong class="fs-5">{{ $order->order_number }}</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <small class="text-muted d-block mb-1">Tổng tiền</small>
                            <strong class="text-danger fs-4">{{ number_format($order->total_amount) }}đ</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <small class="text-muted d-block mb-1">Phương thức thanh toán</small>
                            <strong>
                                @if($order->payment_method === 'VNPAY')
                                    <i class="bi bi-credit-card-2-front me-1"></i>VNPAY
                                @elseif($order->payment_method === 'COD')
                                    <i class="bi bi-cash-coin me-1"></i>Thanh toán khi nhận hàng
                                @else
                                    Chưa chọn
                                @endif
                            </strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <small class="text-muted d-block mb-1">Trạng thái</small>
                            <span class="badge bg-warning">
                                <i class="bi bi-clock me-1"></i>Chờ thanh toán
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($order->payment_method === 'VNPAY')
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-credit-card me-2"></i>Thanh toán qua VNPAY
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Hướng dẫn:</strong> Bạn sẽ được chuyển đến cổng thanh toán VNPAY để hoàn tất thanh toán. 
                    Sau khi thanh toán thành công, bạn sẽ được chuyển về trang đơn hàng.
                </div>

                <form action="{{ route('payment.vnpay', $order->id) }}" method="POST" id="vnpayForm">
                    @csrf
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-credit-card-2-front me-2"></i>Thanh toán qua VNPAY
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @else
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="bi bi-check-circle-fill display-1 text-success d-block mb-3"></i>
                <h4 class="mb-3">Đơn hàng đã được xác nhận!</h4>
                <p class="text-muted mb-4">
                    Bạn đã chọn phương thức thanh toán khi nhận hàng. 
                    Vui lòng chuẩn bị số tiền <strong class="text-danger">{{ number_format($order->total_amount) }}đ</strong> 
                    để thanh toán khi nhận hàng.
                </p>
                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-eye me-2"></i>Xem chi tiết đơn hàng
                </a>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card shadow-lg border-0 sticky-top" style="top: 100px;">
            <div class="card-header bg-gradient text-white">
                <h5 class="mb-0">
                    <i class="bi bi-truck me-2"></i>Thông tin giao hàng
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">
                        <i class="bi bi-person me-1"></i>Người nhận
                    </small>
                    <strong>{{ $order->shipping_name }}</strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">
                        <i class="bi bi-telephone me-1"></i>Điện thoại
                    </small>
                    <strong>{{ $order->shipping_phone }}</strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">
                        <i class="bi bi-geo-alt me-1"></i>Địa chỉ
                    </small>
                    <strong>{{ $order->shipping_address }}</strong>
                </div>
                @if($order->notes)
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">
                        <i class="bi bi-sticky me-1"></i>Ghi chú
                    </small>
                    <p class="mb-0">{{ $order->notes }}</p>
                </div>
                @endif
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Tổng tiền:</span>
                    <strong class="text-danger fs-5">{{ number_format($order->total_amount) }}đ</strong>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const vnpayForm = document.getElementById('vnpayForm');
    if (vnpayForm) {
        vnpayForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang chuyển hướng...';
        });
    }
});
</script>
@endpush
@endsection
