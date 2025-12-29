@extends('layouts.admin')

@section('title', 'Sửa mã giảm giá')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="bi bi-ticket-perforated"></i> Sửa mã giảm giá</h2>
        <p class="text-muted mb-0">Cập nhật thông tin mã giảm giá</p>
    </div>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        Quay lại
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Mã giảm giá <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                               value="{{ old('code', $coupon->code) }}" required>
                        @error('code')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Tên mã giảm giá <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $coupon->name) }}" required>
                        @error('name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $coupon->description) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Loại giảm giá <span class="text-danger">*</span></label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="percentage" {{ old('type', $coupon->type) == 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                            <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>Số tiền cố định (₫)</option>
                        </select>
                        @error('type')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Giá trị <span class="text-danger">*</span></label>
                        <input type="number" name="value" step="0.01" min="0" 
                               class="form-control @error('value') is-invalid @enderror" 
                               value="{{ old('value', $coupon->value) }}" required>
                        @error('value')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Giá trị tối đa (chỉ áp dụng cho %)</label>
                        <input type="number" name="max_discount_amount" step="0.01" min="0" 
                               class="form-control" value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Đơn hàng tối thiểu (₫)</label>
                        <input type="number" name="min_purchase_amount" step="0.01" min="0" 
                               class="form-control" value="{{ old('min_purchase_amount', $coupon->min_purchase_amount) }}">
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Giới hạn sử dụng</label>
                        <input type="number" name="usage_limit" min="1" 
                               class="form-control" value="{{ old('usage_limit', $coupon->usage_limit) }}" 
                               placeholder="Để trống = không giới hạn">
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Giới hạn/người dùng</label>
                        <input type="number" name="usage_limit_per_user" min="1" 
                               class="form-control" value="{{ old('usage_limit_per_user', $coupon->usage_limit_per_user) }}" 
                               placeholder="Để trống = không giới hạn">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Ngày bắt đầu</label>
                        <input type="datetime-local" name="starts_at" 
                               class="form-control" 
                               value="{{ old('starts_at', $coupon->starts_at ? $coupon->starts_at->format('Y-m-d\TH:i') : '') }}">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Ngày kết thúc</label>
                        <input type="datetime-local" name="expires_at" 
                               class="form-control" 
                               value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input type="hidden" name="is_active" value="0">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                           {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Kích hoạt mã giảm giá
                    </label>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i>
                    Cập nhật
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i>
                    Hủy
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

