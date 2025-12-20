@extends('layouts.admin')

@section('title', 'Dashboard - Quản trị')

@section('content')
<h2 class="mb-4">Dashboard</h2>

<div class="row mb-4">
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stats-card primary text-white bg-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white-50 mb-2">Tổng đơn hàng</h6>
                        <h2 class="mb-0">{{ number_format($stats['total_orders']) }}</h2>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-cart-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stats-card warning text-white bg-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white-50 mb-2">Đơn chờ xử lý</h6>
                        <h2 class="mb-0">{{ number_format($stats['pending_orders']) }}</h2>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stats-card success text-white bg-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white-50 mb-2">Tổng doanh thu</h6>
                        <h2 class="mb-0">{{ number_format($stats['total_revenue']) }}đ</h2>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stats-card info text-white bg-info h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-white-50 mb-2">Tổng sản phẩm</h6>
                        <h2 class="mb-0">{{ number_format($stats['total_products']) }}</h2>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Đơn hàng gần đây</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Ngày</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ number_format($order->total_amount) }}đ</td>
                                <td>
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
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Thống kê</h5>
            </div>
            <div class="card-body">
                <p><strong>Tổng người dùng:</strong> {{ number_format($stats['total_users']) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

