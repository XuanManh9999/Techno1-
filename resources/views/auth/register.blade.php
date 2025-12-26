@extends('layouts.app')

@section('title', 'Đăng ký - Techno1')

@section('content')
<!-- Auth Section -->
<section class="auth-section">
    <div class="auth-background">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-60">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="auth-card">
                        <div class="auth-header">
                            <div class="auth-icon">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <h2 class="auth-title">Đăng ký tài khoản</h2>
                            <p class="auth-subtitle">Tạo tài khoản để mua sắm dễ dàng và trải nghiệm tốt nhất</p>
                        </div>
                        <div class="auth-body">
                            <form method="POST" action="{{ route('register') }}" class="auth-form">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="name" class="form-label">
                                                <i class="bi bi-person me-1"></i>Họ và tên <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-wrapper">
                                                <span class="input-icon"><i class="bi bi-person"></i></span>
                                                <input type="text" 
                                                       class="form-control @error('name') is-invalid @enderror" 
                                                       id="name" 
                                                       name="name" 
                                                       value="{{ old('name') }}" 
                                                       placeholder="Nhập họ và tên"
                                                       required>
                                            </div>
                                            @error('name')
                                                <div class="error-message">
                                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-label">
                                                <i class="bi bi-envelope me-1"></i>Email <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-wrapper">
                                                <span class="input-icon"><i class="bi bi-envelope"></i></span>
                                                <input type="email" 
                                                       class="form-control @error('email') is-invalid @enderror" 
                                                       id="email" 
                                                       name="email" 
                                                       value="{{ old('email') }}" 
                                                       placeholder="example@email.com"
                                                       required>
                                            </div>
                                            @error('email')
                                                <div class="error-message">
                                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone" class="form-label">
                                                <i class="bi bi-telephone me-1"></i>Số điện thoại
                                            </label>
                                            <div class="input-wrapper">
                                                <span class="input-icon"><i class="bi bi-telephone"></i></span>
                                                <input type="text" 
                                                       class="form-control @error('phone') is-invalid @enderror" 
                                                       id="phone" 
                                                       name="phone" 
                                                       value="{{ old('phone') }}" 
                                                       placeholder="0123456789">
                                            </div>
                                            @error('phone')
                                                <div class="error-message">
                                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password" class="form-label">
                                                <i class="bi bi-lock me-1"></i>Mật khẩu <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-wrapper">
                                                <span class="input-icon"><i class="bi bi-lock"></i></span>
                                                <input type="password" 
                                                       class="form-control @error('password') is-invalid @enderror" 
                                                       id="password" 
                                                       name="password" 
                                                       placeholder="Tối thiểu 8 ký tự"
                                                       required>
                                                <button class="input-toggle" type="button" id="togglePassword">
                                                    <i class="bi bi-eye" id="eyeIcon"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <div class="error-message">
                                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                            <small class="form-hint">Mật khẩu tối thiểu 8 ký tự</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation" class="form-label">
                                                <i class="bi bi-lock-fill me-1"></i>Xác nhận mật khẩu <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-wrapper">
                                                <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
                                                <input type="password" 
                                                       class="form-control" 
                                                       id="password_confirmation" 
                                                       name="password_confirmation" 
                                                       placeholder="Nhập lại mật khẩu"
                                                       required>
                                                <button class="input-toggle" type="button" id="togglePasswordConfirm">
                                                    <i class="bi bi-eye" id="eyeIconConfirm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-terms mt-4 mb-4">
                                    <div class="form-check-custom">
                                        <input type="checkbox" class="form-check-input" id="terms" required>
                                        <label class="form-check-label" for="terms">
                                            <span class="checkmark"></span>
                                            Tôi đồng ý với <a href="#" class="terms-link">Điều khoản sử dụng</a> và <a href="#" class="terms-link">Chính sách bảo mật</a>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="btn-auth-primary">
                                    <i class="bi bi-person-plus me-2"></i>Đăng ký ngay
                                </button>
                            </form>

                            <div class="auth-divider">
                                <span>Hoặc</span>
                            </div>

                            <div class="auth-footer">
                                <p class="auth-footer-text">
                                    Đã có tài khoản? 
                                    <a href="{{ route('login') }}" class="auth-link">Đăng nhập ngay</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
        
        if (togglePassword) {
            togglePassword.addEventListener('click', function() {
                const password = document.getElementById('password');
                const eyeIcon = document.getElementById('eyeIcon');
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                eyeIcon.classList.toggle('bi-eye');
                eyeIcon.classList.toggle('bi-eye-slash');
            });
        }

        if (togglePasswordConfirm) {
            togglePasswordConfirm.addEventListener('click', function() {
                const password = document.getElementById('password_confirmation');
                const eyeIcon = document.getElementById('eyeIconConfirm');
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                eyeIcon.classList.toggle('bi-eye');
                eyeIcon.classList.toggle('bi-eye-slash');
            });
        }
    });
</script>
@endpush
@endsection

