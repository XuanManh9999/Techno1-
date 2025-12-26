@extends('layouts.app')

@section('title', 'Thông tin cá nhân - Techno1')

@section('content')
<div class="profile-section">
    <div class="container">
        <div class="profile-header">
            <h2>
                <i class="bi bi-person-circle"></i>Thông tin cá nhân
            </h2>
            <p class="profile-subtitle">Quản lý thông tin tài khoản của bạn</p>
        </div>

        <div class="row g-4">
            <!-- Profile Form -->
            <div class="col-lg-8">
                <div class="profile-card card">
                    <div class="card-header">
                        <h5>
                            <i class="bi bi-person me-2"></i>Cập nhật thông tin
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="profile-form-group">
                                <label for="name" class="profile-label">
                                    <i class="bi bi-person me-1"></i>Họ và tên <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control profile-input @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="profile-form-group">
                                <label for="email" class="profile-label">
                                    <i class="bi bi-envelope me-1"></i>Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control profile-input @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="profile-form-group">
                                <label for="phone" class="profile-label">
                                    <i class="bi bi-telephone me-1"></i>Số điện thoại
                                </label>
                                <input type="text" 
                                       class="form-control profile-input @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="profile-form-group">
                                <label for="address" class="profile-label">
                                    <i class="bi bi-geo-alt me-1"></i>Địa chỉ
                                </label>
                                <textarea class="form-control profile-input @error('address') is-invalid @enderror" 
                                          id="address" 
                                          name="address" 
                                          rows="3">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="profile-form-actions">
                                <button type="submit" class="btn btn-primary btn-save">
                                    <i class="bi bi-check-circle me-2"></i>Lưu thay đổi
                                </button>
                                <a href="{{ route('profile.change-password') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-key me-2"></i>Đổi mật khẩu
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Profile Info Sidebar -->
            <div class="col-lg-4">
                <div class="profile-info-card card">
                    <div class="card-header">
                        <h5>
                            <i class="bi bi-info-circle me-2"></i>Thông tin tài khoản
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="profile-info-item">
                            <div class="profile-info-label">
                                <i class="bi bi-calendar3 me-1"></i>Ngày tham gia
                            </div>
                            <div class="profile-info-value">
                                {{ $user->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                        <div class="profile-info-item">
                            <div class="profile-info-label">
                                <i class="bi bi-receipt me-1"></i>Tổng đơn hàng
                            </div>
                            <div class="profile-info-value">
                                {{ $user->orders()->count() }} đơn
                            </div>
                        </div>
                        <div class="profile-info-item">
                            <div class="profile-info-label">
                                <i class="bi bi-cart3 me-1"></i>Sản phẩm trong giỏ
                            </div>
                            <div class="profile-info-value">
                                {{ $user->cartItems()->sum('quantity') }} sản phẩm
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-quick-actions card mt-4">
                    <div class="card-header">
                        <h5>
                            <i class="bi bi-lightning me-2"></i>Thao tác nhanh
                        </h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('orders.index') }}" class="profile-quick-link">
                            <i class="bi bi-receipt"></i>
                            <span>Đơn hàng của tôi</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="{{ route('cart.index') }}" class="profile-quick-link">
                            <i class="bi bi-cart3"></i>
                            <span>Giỏ hàng</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="{{ route('products.index') }}" class="profile-quick-link">
                            <i class="bi bi-grid"></i>
                            <span>Sản phẩm</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

