@extends('layouts.app')

@section('title', 'Quên mật khẩu - Techno1')

@section('content')
<!-- Auth Section -->
<section class="auth-section">
    <div class="auth-background">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-60">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="auth-card">
                        <div class="auth-header">
                            <div class="auth-icon auth-icon-key">
                                <i class="bi bi-key"></i>
                            </div>
                            <h2 class="auth-title">Quên mật khẩu</h2>
                            <p class="auth-subtitle">Nhập email của bạn và chúng tôi sẽ gửi link đặt lại mật khẩu đến email của bạn</p>
                        </div>
                        <div class="auth-body">
                            <form method="POST" action="{{ route('password.email') }}" class="auth-form">
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

                                <button type="submit" class="btn-auth-primary">
                                    <i class="bi bi-send me-2"></i>Gửi link đặt lại mật khẩu
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
@endsection

