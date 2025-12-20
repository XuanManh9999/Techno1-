@extends('layouts.app')

@section('title', 'Đơn hàng của tôi - Techno1')

@section('content')
<div class="d-flex align-items-center mb-4">
    <h2 class="mb-0">
        <i class="bi bi-receipt me-2"></i>Đơn hàng của tôi
    </h2>
</div>

@if($orders->count() > 0)
<div class="card shadow-sm border-0">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="bi bi-list-ul me-2"></i>Danh sách đơn hàng ({{ $orders->total() }})
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
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
                            <strong class="text-primary">#{{ $order->order_number }}</strong>
                        </td>
                        <td>
                            <i class="bi bi-calendar3 me-1 text-muted"></i>
                            {{ $order->created_at->format('d/m/Y') }}
                            <br>
                            <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <strong class="text-danger fs-5">{{ number_format($order->total_amount) }}đ</strong>
                        </td>
                        <td>
                            @if($order->status == 'pending')
                                <span class="badge bg-warning">
                                    <i class="bi bi-clock me-1"></i>Chờ xử lý
                                </span>
                            @elseif($order->status == 'processing')
                                <span class="badge bg-info">
                                    <i class="bi bi-gear me-1"></i>Đang xử lý
                                </span>
                            @elseif($order->status == 'shipped')
                                <span class="badge bg-primary">
                                    <i class="bi bi-truck me-1"></i>Đang giao
                                </span>
                            @elseif($order->status == 'delivered')
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Đã giao
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="bi bi-x-circle me-1"></i>Đã hủy
                                </span>
                            @endif
                        </td>
                        <td>
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
                        </td>
                        <td class="text-center">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary">
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
<div class="card shadow-sm border-0">
    <div class="card-body text-center py-5">
        <i class="bi bi-inbox display-1 text-muted d-block mb-3"></i>
        <h3 class="mb-3">Chưa có đơn hàng nào</h3>
        <p class="text-muted mb-4">Bạn chưa đặt đơn hàng nào. Hãy bắt đầu mua sắm!</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-arrow-right me-2"></i>Mua sắm ngay
        </a>
    </div>
</div>
@endif
@endsection
