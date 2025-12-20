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
                <p><strong>Thanh toán:</strong> 
                    @if($order->payment_status == 'paid')
                        <span class="badge bg-success">Đã thanh toán</span>
                    @elseif($order->payment_status == 'failed')
                        <span class="badge bg-danger">Thanh toán thất bại</span>
                    @else
                        <span class="badge bg-warning">Chưa thanh toán</span>
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

        @if($order->payment_status == 'pending')
        <div class="card mt-3">
            <div class="card-body">
                <a href="{{ route('payment.create', $order->id) }}" class="btn btn-primary w-100">Thanh toán ngay</a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

