@extends('layouts.app')

@section('title', 'Thanh toán - Techno1')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Giỏ hàng</a></li>
        <li class="breadcrumb-item active">Thanh toán</li>
    </ol>
</nav>

<div class="d-flex align-items-center mb-4">
    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h2 class="mb-0">
        <i class="bi bi-credit-card me-2"></i>Thanh toán
    </h2>
</div>

@if($cartItems->count() > 0)
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-truck me-2"></i>Thông tin giao hàng
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person me-1"></i>Họ và tên *
                            </label>
                            <input type="text" 
                                   class="form-control @error('shipping_name') is-invalid @enderror" 
                                   name="shipping_name" 
                                   value="{{ old('shipping_name', auth()->user()->name) }}" 
                                   required>
                            @error('shipping_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-telephone me-1"></i>Số điện thoại *
                            </label>
                            <input type="text" 
                                   class="form-control @error('shipping_phone') is-invalid @enderror" 
                                   name="shipping_phone" 
                                   value="{{ old('shipping_phone', auth()->user()->phone) }}" 
                                   required>
                            @error('shipping_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-geo-alt me-1"></i>Địa chỉ giao hàng *
                            </label>
                            <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                                      name="shipping_address" 
                                      rows="3" 
                                      required>{{ old('shipping_address', auth()->user()->address) }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-sticky me-1"></i>Ghi chú (tùy chọn)
                            </label>
                            <textarea class="form-control" 
                                      name="notes" 
                                      rows="2" 
                                      placeholder="Ghi chú cho người giao hàng...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-check-circle me-2"></i>Xác nhận đặt hàng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-lg border-0 sticky-top" style="top: 100px;">
            <div class="card-header bg-gradient text-white">
                <h5 class="mb-0">
                    <i class="bi bi-receipt me-2"></i>Đơn hàng của bạn
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    @foreach($cartItems as $item)
                    <div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $item->product->name }}</h6>
                            <small class="text-muted">x{{ $item->quantity }}</small>
                        </div>
                        <strong class="text-danger ms-2">{{ number_format($item->subtotal) }}đ</strong>
                    </div>
                    @endforeach
                </div>
                <hr>
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
            </div>
        </div>
    </div>
</div>
@else
<div class="card shadow-sm border-0">
    <div class="card-body text-center py-5">
        <i class="bi bi-cart-x display-1 text-muted d-block mb-3"></i>
        <h3 class="mb-3">Giỏ hàng trống</h3>
        <p class="text-muted mb-4">Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-arrow-right me-2"></i>Tiếp tục mua sắm
        </a>
    </div>
</div>
@endif
@endsection
