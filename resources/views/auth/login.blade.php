@extends('layouts.app')

@section('title', 'Đăng nhập - Techno1')

@section('content')
<!-- Auth Section -->
<section class="auth-section">
    <div class="auth-background">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-60">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="auth-card">
                        <div class="auth-header">
                            <div class="auth-icon">
                                <i class="bi bi-box-arrow-in-right"></i>
                            </div>
                            <h2 class="auth-title">Đăng nhập</h2>
                            <p class="auth-subtitle">Chào mừng bạn trở lại! Vui lòng đăng nhập vào tài khoản của bạn</p>
                        </div>
                        <div class="auth-body">
                            <form method="POST" action="{{ route('login') }}" class="auth-form">
                                @csrf

                                <div class="form-group">
                                    <label for="email" class="form-label">
                                        <i class="bi bi-envelope me-1"></i>Email
                                    </label>
                                    <div class="input-wrapper">
                                        <span class="input-icon"><i class="bi bi-envelope"></i></span>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email') }}" 
                                               placeholder="Nhập email của bạn"
                                               required 
                                               autofocus>
                                    </div>
                                    @error('email')
                                        <div class="error-message">
                                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password" class="form-label">
                                        <i class="bi bi-lock me-1"></i>Mật khẩu
                                    </label>
                                    <div class="input-wrapper">
                                        <span class="input-icon"><i class="bi bi-lock"></i></span>
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Nhập mật khẩu"
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
                                </div>

                                <div class="form-options">
                                    <div class="form-check-custom">
                                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">
                                            <span class="checkmark"></span>
                                            Ghi nhớ đăng nhập
                                        </label>
                                    </div>
                                    <a href="{{ route('password.request') }}" class="forgot-link">
                                        Quên mật khẩu?
                                    </a>
                                </div>

                                <button type="submit" class="btn-auth-primary">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập
                                </button>
                            </form>

                            <div class="auth-divider">
                                <span>Hoặc</span>
                            </div>

                            <div class="auth-footer">
                                <p class="auth-footer-text">
                                    Chưa có tài khoản? 
                                    <a href="{{ route('register') }}" class="auth-link">Đăng ký ngay</a>
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
    });
</script>
@endpush
@endsection

