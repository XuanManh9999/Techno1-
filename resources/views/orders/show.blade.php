@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng - Techno1')

@section('content')
<h2 class="mb-4">Chi tiết đơn hàng</h2>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Thông tin đơn hàng</h5>
            </div>
            <div class="card-body">
                <p><strong>Mã đơn hàng:</strong> {{ $order->order_number }}</p>
                <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Trạng thái:</strong> 
                    @if($order->status == 'pending')
                        <span class="badge bg-warning">Chờ xử lý</span>
                    @elseif($order->status == 'processing')
                        <span class="badge bg-info">Đang xử lý</span>
                    @elseif($order->status == 'shipped')
                        <span class="badge bg-primary">Đang giao</span>
                    @elseif($order->status == 'delivered')
                        <span class="badge bg-success">Đã giao</span>
                    @else
                        <span class="badge bg-danger">Đã hủy</span>
                    @endif
                </p>
                <p><strong>Phương thức thanh toán:</strong> 
                    @if($order->payment_method === 'VNPAY')
                        <span class="badge bg-primary">
                            <i class="bi bi-credit-card-2-front me-1"></i>VNPAY
                        </span>
                    @elseif($order->payment_method === 'COD')
                        <span class="badge bg-success">
                            <i class="bi bi-cash-coin me-1"></i>Thanh toán khi nhận hàng
                        </span>
                    @else
                        <span class="badge bg-secondary">Chưa chọn</span>
                    @endif
                </p>
                <p><strong>Trạng thái thanh toán:</strong> 
                    @if($order->payment_status == 'paid')
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle me-1"></i>Đã thanh toán
                        </span>
                    @elseif($order->payment_status == 'failed')
                        <span class="badge bg-danger">
                            <i class="bi bi-x-circle me-1"></i>Thanh toán thất bại
                        </span>
                    @else
                        <span class="badge bg-warning">
                            <i class="bi bi-clock me-1"></i>Chưa thanh toán
                        </span>
                    @endif
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Sản phẩm</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ $item->product_name }}</strong>
                                    @if($item->variant_attributes)
                                    <div class="small text-muted mt-1">
                                        @foreach($item->variant_attributes as $key => $value)
                                            <span class="badge bg-light text-dark me-1">{{ $key }}: {{ $value }}</span>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price) }}đ</td>
                            <td>{{ number_format($item->subtotal) }}đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                            <td><strong>{{ number_format($order->total_amount) }}đ</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Thông tin giao hàng</h5>
            </div>
            <div class="card-body">
                <p><strong>Người nhận:</strong> {{ $order->shipping_name }}</p>
                <p><strong>Điện thoại:</strong> {{ $order->shipping_phone }}</p>
                <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                @if($order->notes)
                    <p><strong>Ghi chú:</strong> {{ $order->notes }}</p>
                @endif
            </div>
        </div>

        @if($order->payment_status == 'pending' && $order->payment_method === 'VNPAY')
        <div class="card mt-3 shadow-sm border-0">
            <div class="card-body">
                <h6 class="mb-3">
                    <i class="bi bi-credit-card me-2"></i>Thanh toán đơn hàng
                </h6>
                <a href="{{ route('payment.create', $order->id) }}" class="btn btn-primary w-100 btn-lg">
                    <i class="bi bi-credit-card-2-front me-2"></i>Thanh toán qua VNPAY
                </a>
                <small class="text-muted d-block mt-2 text-center">
                    <i class="bi bi-info-circle me-1"></i>Thanh toán trực tuyến an toàn
                </small>
            </div>
        </div>
        @elseif($order->payment_status == 'pending' && $order->payment_method === 'COD')
        <div class="card mt-3 shadow-sm border-0">
            <div class="card-body text-center">
                <i class="bi bi-cash-coin display-4 text-success d-block mb-3"></i>
                <h6 class="mb-2">Thanh toán khi nhận hàng</h6>
                <p class="text-muted small mb-0">
                    Bạn sẽ thanh toán <strong class="text-danger">{{ number_format($order->total_amount) }}đ</strong> 
                    khi nhận hàng
                </p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

