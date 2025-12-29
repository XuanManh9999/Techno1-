@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-cart-check"></i> Quản lý đơn hàng</h2>
</div>

<!-- Search and Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tìm kiếm</label>
                <input type="text" name="search" class="form-control" 
                       value="{{ request('search') }}" 
                       placeholder="Mã đơn, tên, email, SĐT khách hàng...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đang giao</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Thanh toán</label>
                <select name="payment_status" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Chưa thanh toán</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Thất bại</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Tìm kiếm
                </button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Thanh toán</th>
                        <th>Ngày đặt</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>
                            <div>
                                <strong>{{ $order->user->name }}</strong><br>
                                <small class="text-muted">{{ $order->user->email }}</small>
                            </div>
                        </td>
                        <td><strong class="text-danger">{{ number_format($order->total_amount) }}đ</strong></td>
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
                        <td>
                            @if($order->payment_status == 'paid')
                                <span class="badge bg-success">Đã thanh toán</span>
                            @elseif($order->payment_status == 'failed')
                                <span class="badge bg-danger">Thất bại</span>
                            @else
                                <span class="badge bg-warning">Chưa thanh toán</span>
                            @endif
                        </td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                               class="btn btn-sm btn-primary" 
                               title="Xem chi tiết">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                            <p class="text-muted">Không tìm thấy đơn hàng nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="admin-pagination">
            {{ $orders->links('vendor.pagination.custom') }}
        </div>
        @endif
    </div>
</div>
@endsection
