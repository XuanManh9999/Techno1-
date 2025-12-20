@extends('layouts.app')

@section('title', 'Giỏ hàng - Techno1')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h2 class="mb-0">
        <i class="bi bi-cart3 me-2"></i>Giỏ hàng của tôi
    </h2>
</div>

@if($cartItems->count() > 0)
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-bag me-2"></i>Sản phẩm trong giỏ hàng ({{ $cartItems->count() }})
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50%;">Sản phẩm</th>
                                <th class="text-center">Giá</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-center">Thành tiền</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center p-3">
                                        <div class="img-zoom me-3" style="width: 100px; height: 100px; border-radius: var(--radius-sm); overflow: hidden;">
                                            <img src="{{ $item->product->image ?? 'https://via.placeholder.com/100' }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="w-100 h-100" 
                                                 style="object-fit: cover;">
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-semibold">{{ $item->product->name }}</h6>
                                            <small class="text-muted d-block">
                                                <i class="bi bi-upc-scan me-1"></i>SKU: {{ $item->product->sku }}
                                            </small>
                                            @if($item->product->sale_price)
                                                <small class="text-muted">
                                                    <span class="text-decoration-line-through">{{ number_format($item->product->price) }}đ</span>
                                                    <span class="text-danger ms-1">{{ number_format($item->product->sale_price) }}đ</span>
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-center">
                                    <strong class="text-danger">{{ number_format($item->product->final_price) }}đ</strong>
                                </td>
                                <td class="align-middle">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex justify-content-center">
                                        @csrf
                                        @method('PUT')
                                        <div class="input-group" style="width: 120px;">
                                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="this.parentElement.querySelector('input').stepDown(); this.form.submit();">-</button>
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                                   min="1" max="{{ $item->product->stock_quantity }}" 
                                                   class="form-control form-control-sm text-center" 
                                                   onchange="this.form.submit()">
                                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="this.parentElement.querySelector('input').stepUp(); this.form.submit();">+</button>
                                        </div>
                                    </form>
                                </td>
                                <td class="align-middle text-center">
                                    <strong class="text-danger fs-5">{{ number_format($item->subtotal) }}đ</strong>
                                </td>
                                <td class="align-middle text-center">
                                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');"
                                                title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-lg border-0 sticky-top" style="top: 100px;">
            <div class="card-header bg-gradient text-white">
                <h5 class="mb-0">
                    <i class="bi bi-receipt me-2"></i>Tổng kết đơn hàng
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Tạm tính:</span>
                    <strong>{{ number_format($total) }}đ</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Phí vận chuyển:</span>
                    <strong class="text-success">Miễn phí</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-bold fs-5">Tổng cộng:</span>
                    <strong class="text-danger fs-4">{{ number_format($total) }}đ</strong>
                </div>
                <div class="d-grid gap-2">
                    <a href="{{ route('cart.checkout') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-credit-card me-2"></i>Thanh toán ngay
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="card shadow-sm border-0">
    <div class="card-body text-center py-5">
        <i class="bi bi-cart-x display-1 text-muted d-block mb-3"></i>
        <h3 class="mb-3">Giỏ hàng trống</h3>
        <p class="text-muted mb-4">Bạn chưa có sản phẩm nào trong giỏ hàng. Hãy bắt đầu mua sắm!</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-arrow-right me-2"></i>Tiếp tục mua sắm
        </a>
    </div>
</div>
@endif
@endsection
