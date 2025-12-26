@extends('layouts.app')

@section('title', 'Đơn hàng của tôi - Techno1')

@section('content')
<div class="orders-section">
    <div class="container">
        <div class="orders-header">
            <h2>
                <i class="bi bi-receipt"></i>Đơn hàng của tôi
            </h2>
        </div>

        @if($orders->count() > 0)
        <div class="orders-card card">
            <div class="card-header">
                <h5>
                    <i class="bi bi-list-ul"></i>Danh sách đơn hàng ({{ $orders->total() }})
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="orders-table-wrapper">
                    <table class="orders-table table">
                        <thead>
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Thanh toán</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>
                                    <span class="order-number">#{{ $order->order_number }}</span>
                                </td>
                                <td>
                                    <div class="order-date">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ $order->created_at->format('d/m/Y') }}
                                        <small>{{ $order->created_at->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="order-amount">{{ number_format($order->total_amount) }}₫</span>
                                </td>
                                <td>
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
                                </td>
                                <td>
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
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-detail">
                                        <i class="bi bi-eye me-1"></i>Chi tiết
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($orders->hasPages())
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
        @endif
        @else
        <div class="orders-card card">
            <div class="card-body orders-empty">
                <i class="bi bi-inbox"></i>
                <h3>Chưa có đơn hàng nào</h3>
                <p>Bạn chưa đặt đơn hàng nào. Hãy bắt đầu mua sắm!</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-arrow-right me-2"></i>Mua sắm ngay
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
