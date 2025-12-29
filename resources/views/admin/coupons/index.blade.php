@extends('layouts.admin')

@section('title', 'Quản lý mã giảm giá')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="bi bi-ticket-perforated"></i> Quản lý mã giảm giá</h2>
        <p class="text-muted mb-0">Quản lý các mã giảm giá và khuyến mãi</p>
    </div>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i>
        Thêm mã giảm giá
    </a>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.coupons.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tìm kiếm</label>
                <input type="text" name="search" class="form-control" 
                       value="{{ request('search') }}" 
                       placeholder="Mã, tên, mô tả...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Loại</label>
                <select name="type" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="percentage" {{ request('type') == 'percentage' ? 'selected' : '' }}>Phần trăm</option>
                    <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Trạng thái</label>
                <select name="is_active" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Kích hoạt</option>
                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Vô hiệu</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Hết hạn</label>
                <select name="expired" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="0" {{ request('expired') == '0' ? 'selected' : '' }}>Còn hiệu lực</option>
                    <option value="1" {{ request('expired') == '1' ? 'selected' : '' }}>Đã hết hạn</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-search"></i> Tìm kiếm
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Coupons Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-list-ul"></i>
            Danh sách mã giảm giá
            <span class="badge bg-primary ms-2">{{ $coupons->total() }}</span>
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Mã</th>
                        <th>Tên</th>
                        <th style="width: 120px;">Loại</th>
                        <th style="width: 120px;">Giá trị</th>
                        <th style="width: 120px;">Đã sử dụng</th>
                        <th style="width: 120px;">Hạn sử dụng</th>
                        <th style="width: 100px;">Trạng thái</th>
                        <th style="width: 120px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                    <tr>
                        <td><strong>#{{ $coupon->id }}</strong></td>
                        <td>
                            <code class="text-primary fw-bold">{{ $coupon->code }}</code>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $coupon->name }}</strong>
                            </div>
                            @if($coupon->description)
                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($coupon->description, 50) }}</small>
                            @endif
                        </td>
                        <td>
                            @if($coupon->type == 'percentage')
                                <span class="badge bg-info">
                                    <i class="bi bi-percent"></i> Phần trăm
                                </span>
                            @else
                                <span class="badge bg-success">
                                    <i class="bi bi-currency-dollar"></i> Cố định
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($coupon->type == 'percentage')
                                <strong>{{ $coupon->value }}%</strong>
                            @else
                                <strong>{{ number_format($coupon->value) }}₫</strong>
                            @endif
                            @if($coupon->min_purchase_amount)
                                <br><small class="text-muted">Tối thiểu: {{ number_format($coupon->min_purchase_amount) }}₫</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary">
                                {{ $coupon->used_count }}
                                @if($coupon->usage_limit)
                                    / {{ $coupon->usage_limit }}
                                @else
                                    / ∞
                                @endif
                            </span>
                        </td>
                        <td>
                            @if($coupon->expires_at)
                                <small>
                                    {{ $coupon->expires_at->format('d/m/Y') }}
                                    @if($coupon->expires_at->isPast())
                                        <br><span class="text-danger">Đã hết hạn</span>
                                    @endif
                                </small>
                            @else
                                <span class="text-muted">Không giới hạn</span>
                            @endif
                        </td>
                        <td>
                            @if($coupon->is_active)
                                <span class="badge bg-success">Kích hoạt</span>
                            @else
                                <span class="badge bg-danger">Vô hiệu</span>
                            @endif
                            @if($coupon->is_active && !$coupon->isActive())
                                <br><small class="text-warning">(Đã hết hạn hoặc hết lượt)</small>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}" 
                                   class="btn btn-warning" 
                                   title="Sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa mã giảm giá này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted mb-0">Không tìm thấy mã giảm giá nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($coupons->hasPages())
        <div class="admin-pagination">
            {{ $coupons->links('vendor.pagination.custom') }}
        </div>
        @endif
    </div>
</div>
@endsection

