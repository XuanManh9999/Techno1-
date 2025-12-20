@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')

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
                <p><strong>Khách hàng:</strong> {{ $order->user->name }} ({{ $order->user->email }})</p>
                <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Tổng tiền:</strong> <span class="text-danger fs-4">{{ number_format($order->total_amount) }}đ</span></p>
            </div>
        </div>

        <div class="card mb-4">
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
                            <td>{{ $item->product->name }}</td>
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
        <div class="card mb-3">
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

        <div class="card mb-3">
            <div class="card-header">
                <h5>Cập nhật trạng thái</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Đang giao</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Đã giao</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Cập nhật</button>
                </form>
            </div>
        </div>

        @if($order->payment)
        <div class="card">
            <div class="card-header">
                <h5>Thông tin thanh toán</h5>
            </div>
            <div class="card-body">
                <p><strong>Phương thức:</strong> {{ $order->payment->payment_method }}</p>
                <p><strong>Số tiền:</strong> {{ number_format($order->payment->amount) }}đ</p>
                <p><strong>Trạng thái:</strong> 
                    @if($order->payment->status == 'success')
                        <span class="badge bg-success">Thành công</span>
                    @elseif($order->payment->status == 'failed')
                        <span class="badge bg-danger">Thất bại</span>
                    @else
                        <span class="badge bg-warning">Chờ xử lý</span>
                    @endif
                </p>
                @if($order->payment->vnpay_transaction_no)
                    <p><strong>Mã giao dịch:</strong> {{ $order->payment->vnpay_transaction_no }}</p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

