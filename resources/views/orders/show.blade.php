@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng - Techno1')

@section('content')
<div class="order-detail-section">
    <div class="container">
        <div class="order-detail-header">
            <h2>
                <i class="bi bi-receipt-cutoff"></i>Chi tiết đơn hàng
            </h2>
        </div>

        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Order Information Card -->
                <div class="order-detail-card card mb-4">
                    <div class="card-header">
                        <h5>
                            <i class="bi bi-info-circle me-2"></i>Thông tin đơn hàng
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="order-info-grid">
                            <div class="order-info-item">
                                <div class="order-info-label">
                                    <i class="bi bi-hash me-1"></i>Mã đơn hàng
                                </div>
                                <div class="order-info-value">
                                    <strong class="order-number">#{{ $order->order_number }}</strong>
                                </div>
                            </div>
                            <div class="order-info-item">
                                <div class="order-info-label">
                                    <i class="bi bi-calendar3 me-1"></i>Ngày đặt
                                </div>
                                <div class="order-info-value">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                            <div class="order-info-item">
                                <div class="order-info-label">
                                    <i class="bi bi-clock-history me-1"></i>Trạng thái
                                </div>
                                <div class="order-info-value">
                                    @if($order->status == 'pending')
                                        <span class="order-status-badge bg-warning text-dark">
                                            <i class="bi bi-clock"></i>Chờ xử lý
                                        </span>
                                    @elseif($order->status == 'processing')
                                        <span class="order-status-badge bg-info text-white">
                                            <i class="bi bi-gear"></i>Đang xử lý
                                        </span>
                                    @elseif($order->status == 'shipped')
                                        <span class="order-status-badge bg-primary text-white">
                                            <i class="bi bi-truck"></i>Đang giao
                                        </span>
                                    @elseif($order->status == 'delivered')
                                        <span class="order-status-badge bg-success text-white">
                                            <i class="bi bi-check-circle"></i>Đã giao
                                        </span>
                                    @else
                                        <span class="order-status-badge bg-danger text-white">
                                            <i class="bi bi-x-circle"></i>Đã hủy
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="order-info-item">
                                <div class="order-info-label">
                                    <i class="bi bi-credit-card me-1"></i>Phương thức thanh toán
                                </div>
                                <div class="order-info-value">
                                    @if($order->payment_method === 'VNPAY')
                                        <span class="payment-method-badge bg-primary text-white">
                                            <i class="bi bi-credit-card-2-front"></i>VNPAY
                                        </span>
                                    @elseif($order->payment_method === 'COD')
                                        <span class="payment-method-badge bg-success text-white">
                                            <i class="bi bi-cash-coin"></i>Thanh toán khi nhận hàng
                                        </span>
                                    @else
                                        <span class="payment-method-badge bg-secondary text-white">Chưa chọn</span>
                                    @endif
                                </div>
                            </div>
                            <div class="order-info-item">
                                <div class="order-info-label">
                                    <i class="bi bi-wallet2 me-1"></i>Trạng thái thanh toán
                                </div>
                                <div class="order-info-value">
                                    @if($order->payment_status == 'paid')
                                        <span class="payment-status-badge bg-success text-white">
                                            <i class="bi bi-check-circle"></i>Đã thanh toán
                                        </span>
                                    @elseif($order->payment_status == 'failed')
                                        <span class="payment-status-badge bg-danger text-white">
                                            <i class="bi bi-x-circle"></i>Thanh toán thất bại
                                        </span>
                                    @else
                                        <span class="payment-status-badge bg-warning text-dark">
                                            <i class="bi bi-clock"></i>Chưa thanh toán
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Card -->
                <div class="order-detail-card card">
                    <div class="card-header">
                        <h5>
                            <i class="bi bi-box-seam me-2"></i>Sản phẩm
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="order-products-table-wrapper">
                            <table class="order-products-table table mb-0">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th class="text-center">Số lượng</th>
                                        <th class="text-end">Giá</th>
                                        <th class="text-end">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="order-product-info">
                                                <strong class="order-product-name">{{ $item->product_name }}</strong>
                                                @if($item->variant_attributes)
                                                <div class="order-product-variants mt-1">
                                                    @foreach($item->variant_attributes as $key => $value)
                                                        <span class="variant-badge">{{ $key }}: {{ $value }}</span>
                                                    @endforeach
                                                </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="order-product-quantity">{{ $item->quantity }}</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="order-product-price">{{ number_format($item->price) }}₫</span>
                                        </td>
                                        <td class="text-end">
                                            <strong class="order-product-subtotal">{{ number_format($item->subtotal) }}₫</strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="order-total-row">
                                        <td colspan="3" class="text-end">
                                            <strong class="order-total-label">Tổng cộng:</strong>
                                        </td>
                                        <td class="text-end">
                                            <strong class="order-total-amount">{{ number_format($order->total_amount) }}₫</strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Shipping Information Card -->
                <div class="order-detail-card card mb-4">
                    <div class="card-header">
                        <h5>
                            <i class="bi bi-truck me-2"></i>Thông tin giao hàng
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="shipping-info">
                            <div class="shipping-info-item">
                                <div class="shipping-info-label">
                                    <i class="bi bi-person me-1"></i>Người nhận
                                </div>
                                <div class="shipping-info-value">
                                    <strong>{{ $order->shipping_name }}</strong>
                                </div>
                            </div>
                            <div class="shipping-info-item">
                                <div class="shipping-info-label">
                                    <i class="bi bi-telephone me-1"></i>Điện thoại
                                </div>
                                <div class="shipping-info-value">
                                    {{ $order->shipping_phone }}
                                </div>
                            </div>
                            <div class="shipping-info-item">
                                <div class="shipping-info-label">
                                    <i class="bi bi-geo-alt me-1"></i>Địa chỉ
                                </div>
                                <div class="shipping-info-value">
                                    {{ $order->shipping_address }}
                                </div>
                            </div>
                            @if($order->notes)
                            <div class="shipping-info-item">
                                <div class="shipping-info-label">
                                    <i class="bi bi-sticky me-1"></i>Ghi chú
                                </div>
                                <div class="shipping-info-value">
                                    {{ $order->notes }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Payment Card -->
                @if($order->payment_status == 'pending' && $order->payment_method === 'VNPAY')
                <div class="order-payment-card card">
                    <div class="card-body">
                        <div class="payment-card-header">
                            <i class="bi bi-credit-card"></i>
                            <h6>Thanh toán đơn hàng</h6>
                        </div>
                        <a href="{{ route('payment.create', $order->id) }}" class="btn btn-primary btn-payment w-100">
                            <i class="bi bi-credit-card-2-front me-2"></i>Thanh toán qua VNPAY
                        </a>
                        <small class="payment-note">
                            <i class="bi bi-info-circle me-1"></i>Thanh toán trực tuyến an toàn
                        </small>
                    </div>
                </div>
                @elseif($order->payment_status == 'pending' && $order->payment_method === 'COD')
                <div class="order-payment-card card">
                    <div class="card-body text-center">
                        <div class="cod-icon-wrapper">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <h6 class="cod-title">Thanh toán khi nhận hàng</h6>
                        <p class="cod-message">
                            Bạn sẽ thanh toán <strong class="cod-amount">{{ number_format($order->total_amount) }}₫</strong> 
                            khi nhận hàng
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

