@extends('layouts.app')

@section('title', 'Đặt lại mật khẩu - Techno1')

@section('content')
<!-- Auth Section -->
<section class="auth-section">
    <div class="auth-background">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-60">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="auth-card">
                        <div class="auth-header">
                            <div class="auth-icon auth-icon-shield">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <h2 class="auth-title">Đặt lại mật khẩu</h2>
                            <p class="auth-subtitle">Vui lòng nhập mật khẩu mới cho tài khoản của bạn</p>
                        </div>
                        <div class="auth-body">
                            <form method="POST" action="{{ route('password.update') }}" class="auth-form">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">

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
                                               value="{{ $email ?? old('email') }}" 
                                               required 
                                               autofocus 
                                               readonly>
                                    </div>
                                    @error('email')
                                        <div class="error-message">
                                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password" class="form-label">
                                        <i class="bi bi-lock me-1"></i>Mật khẩu mới
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

                                <div class="form-group">
                                    <label for="password-confirm" class="form-label">
                                        <i class="bi bi-lock-fill me-1"></i>Xác nhận mật khẩu
                                    </label>
                                    <div class="input-wrapper">
                                        <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" 
                                               class="form-control" 
                                               id="password-confirm" 
                                               name="password_confirmation" 
                                               placeholder="Nhập lại mật khẩu"
                                               required>
                                        <button class="input-toggle" type="button" id="togglePasswordConfirm">
                                            <i class="bi bi-eye" id="eyeIconConfirm"></i>
                                        </button>
                                    </div>
                                </div>

                                <button type="submit" class="btn-auth-primary">
                                    <i class="bi bi-check-circle me-2"></i>Đặt lại mật khẩu
                                </button>
                            </form>

                            <div class="auth-footer">
                                <a href="{{ route('login') }}" class="auth-link-back">
                                    <i class="bi bi-arrow-left me-1"></i>Quay lại đăng nhập
                                </a>
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
                const password = document.getElementById('password-confirm');
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

