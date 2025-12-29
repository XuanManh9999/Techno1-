@extends('layouts.admin')

@section('title', 'Dashboard - Quản trị')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </h1>
        <p class="admin-page-subtitle">Tổng quan hệ thống và thống kê</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Tổng đơn hàng</span>
            <div class="stat-card-icon primary">
                <i class="bi bi-cart-check"></i>
            </div>
        </div>
        <h3 class="stat-card-value">{{ number_format($stats['total_orders']) }}</h3>
        <div class="stat-card-footer">
            <i class="bi bi-arrow-up text-success"></i> Tất cả đơn hàng
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Đơn chờ xử lý</span>
            <div class="stat-card-icon warning">
                <i class="bi bi-clock-history"></i>
            </div>
        </div>
        <h3 class="stat-card-value">{{ number_format($stats['pending_orders']) }}</h3>
        <div class="stat-card-footer">
            <i class="bi bi-exclamation-circle text-warning"></i> Cần xử lý ngay
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Tổng doanh thu</span>
            <div class="stat-card-icon success">
                <i class="bi bi-currency-dollar"></i>
            </div>
        </div>
        <h3 class="stat-card-value">{{ number_format($stats['total_revenue']) }}₫</h3>
        <div class="stat-card-footer">
            <i class="bi bi-graph-up-arrow text-success"></i> Đã thanh toán
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Tổng sản phẩm</span>
            <div class="stat-card-icon info">
                <i class="bi bi-box-seam"></i>
            </div>
        </div>
        <h3 class="stat-card-value">{{ number_format($stats['total_products']) }}</h3>
        <div class="stat-card-footer">
            <i class="bi bi-box text-info"></i> Trong hệ thống
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Orders -->
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title">
                    <i class="bi bi-list-ul"></i>
                    Đơn hàng gần đây
                </h2>
                <a href="{{ route('admin.orders.index') }}" class="btn-admin btn-admin-sm btn-admin-primary">
                    Xem tất cả
                </a>
            </div>
            <div class="admin-card-body">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Ngày</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td><strong>{{ $order->order_number }}</strong></td>
                                <td>{{ $order->user->name }}</td>
                                <td><strong>{{ number_format($order->total_amount) }}₫</strong></td>
                                <td>
                                    @if($order->status == 'pending')
                                        <span class="admin-badge badge-warning">Chờ xử lý</span>
                                    @elseif($order->status == 'processing')
                                        <span class="admin-badge badge-info">Đang xử lý</span>
                                    @elseif($order->status == 'shipped')
                                        <span class="admin-badge badge-primary">Đang giao</span>
                                    @elseif($order->status == 'delivered')
                                        <span class="admin-badge badge-success">Đã giao</span>
                                    @else
                                        <span class="admin-badge badge-danger">Đã hủy</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" 
                                       class="btn-admin btn-admin-sm btn-admin-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-muted mb-0">Chưa có đơn hàng nào</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-lg-4">
        <div class="admin-card mb-4">
            <div class="admin-card-header">
                <h2 class="admin-card-title">
                    <i class="bi bi-graph-up"></i>
                    Thống kê nhanh
                </h2>
            </div>
            <div class="admin-card-body">
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Tổng người dùng</span>
                        <strong>{{ number_format($stats['total_users']) }}</strong>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="btn-admin btn-admin-sm btn-admin-secondary w-100">
                        <i class="bi bi-people"></i> Quản lý người dùng
                    </a>
                </div>
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Đơn đã giao</span>
                        <strong>{{ \App\Models\Order::where('status', 'delivered')->count() }}</strong>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Đơn đã hủy</span>
                        <strong>{{ \App\Models\Order::where('status', 'cancelled')->count() }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title">
                    <i class="bi bi-lightning-charge"></i>
                    Thao tác nhanh
                </h2>
            </div>
            <div class="admin-card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.products.create') }}" class="btn-admin btn-admin-primary">
                        <i class="bi bi-plus-circle"></i> Thêm sản phẩm
                    </a>
                    <a href="{{ route('admin.coupons.create') }}" class="btn-admin btn-admin-success">
                        <i class="bi bi-ticket-perforated"></i> Tạo mã giảm giá
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="btn-admin btn-admin-info">
                        <i class="bi bi-cart-check"></i> Xem đơn hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
