@extends('layouts.app')

@section('title', 'Đổi mật khẩu - Techno1')

@section('content')
<div class="profile-section">
    <div class="container">
        <div class="profile-header">
            <h2>
                <i class="bi bi-key"></i>Đổi mật khẩu
            </h2>
            <p class="profile-subtitle">Bảo mật tài khoản của bạn bằng mật khẩu mạnh</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="profile-card card">
                    <div class="card-header">
                        <h5>
                            <i class="bi bi-shield-lock me-2"></i>Cập nhật mật khẩu
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.change-password.post') }}" method="POST">
                            @csrf

                            <div class="profile-form-group">
                                <label for="current_password" class="profile-label">
                                    <i class="bi bi-lock me-1"></i>Mật khẩu hiện tại <span class="text-danger">*</span>
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password" 
                                           class="form-control profile-input @error('current_password') is-invalid @enderror" 
                                           id="current_password" 
                                           name="current_password" 
                                           required>
                                    <button type="button" class="password-toggle" onclick="togglePassword('current_password')">
                                        <i class="bi bi-eye" id="current_password_icon"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="profile-form-group">
                                <label for="password" class="profile-label">
                                    <i class="bi bi-key me-1"></i>Mật khẩu mới <span class="text-danger">*</span>
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password" 
                                           class="form-control profile-input @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           required>
                                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                        <i class="bi bi-eye" id="password_icon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Mật khẩu phải có ít nhất 8 ký tự
                                </small>
                            </div>

                            <div class="profile-form-group">
                                <label for="password_confirmation" class="profile-label">
                                    <i class="bi bi-key-fill me-1"></i>Xác nhận mật khẩu mới <span class="text-danger">*</span>
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password" 
                                           class="form-control profile-input" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           required>
                                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                        <i class="bi bi-eye" id="password_confirmation_icon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="profile-form-actions">
                                <button type="submit" class="btn btn-primary btn-save">
                                    <i class="bi bi-check-circle me-2"></i>Đổi mật khẩu
                                </button>
                                <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Quay lại
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="profile-security-tips card mt-4">
                    <div class="card-body">
                        <h6 class="mb-3">
                            <i class="bi bi-shield-check me-2"></i>Mẹo bảo mật
                        </h6>
                        <ul class="security-tips-list">
                            <li>
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Sử dụng mật khẩu có ít nhất 8 ký tự
                            </li>
                            <li>
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Kết hợp chữ hoa, chữ thường, số và ký tự đặc biệt
                            </li>
                            <li>
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Không sử dụng thông tin cá nhân trong mật khẩu
                            </li>
                            <li>
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Đổi mật khẩu định kỳ để bảo mật tốt hơn
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '_icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>
@endpush
@endsection

