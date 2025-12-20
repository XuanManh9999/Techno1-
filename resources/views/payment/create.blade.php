@extends('layouts.app')

@section('title', 'Thanh toán - Techno1')

@section('content')
<h2 class="mb-4">Thanh toán đơn hàng</h2>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Thông tin đơn hàng</h5>
            </div>
            <div class="card-body">
                <p><strong>Mã đơn hàng:</strong> {{ $order->order_number }}</p>
                <p><strong>Tổng tiền:</strong> <span class="text-danger fs-4">{{ number_format($order->total_amount) }}đ</span></p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Chọn phương thức thanh toán</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('payment.vnpay', $order->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="vnpay" value="vnpay" checked>
                            <label class="form-check-label" for="vnpay">
                                <strong>VNPAY</strong> - Thanh toán qua cổng VNPAY
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">Thanh toán</button>
                </form>
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
            </div>
        </div>
    </div>
</div>
@endsection

