@extends('layouts.admin')

@section('title', 'Quản lý thương hiệu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-award"></i> Quản lý thương hiệu</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBrandModal">
        <i class="bi bi-plus-circle"></i> Thêm thương hiệu
    </button>
</div>

<!-- Search and Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.brands.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tìm kiếm</label>
                <input type="text" name="search" class="form-control" 
                       value="{{ request('search') }}" 
                       placeholder="Tên, mô tả...">
            </div>
            <div class="col-md-3">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-search"></i> Tìm kiếm
                </button>
                <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên thương hiệu</th>
                        <th>Slug</th>
                        <th>Mô tả</th>
                        <th>Số sản phẩm</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brands as $brand)
                    <tr>
                        <td>{{ $brand->id }}</td>
                        <td><strong>{{ $brand->name }}</strong></td>
                        <td><code>{{ $brand->slug }}</code></td>
                        <td>{{ \Illuminate\Support\Str::limit($brand->description ?? '', 50) ?: '-' }}</td>
                        <td>
                            <span class="badge bg-info">{{ $brand->products()->count() }}</span>
                        </td>
                        <td>
                            @if($brand->status)
                                <span class="badge bg-success">Hiển thị</span>
                            @else
                                <span class="badge bg-secondary">Ẩn</span>
                            @endif
                        </td>
                        <td>{{ $brand->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button type="button" 
                                        class="btn btn-warning edit-brand-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editBrandModal"
                                        data-id="{{ $brand->id }}"
                                        data-name="{{ $brand->name }}"
                                        data-description="{{ $brand->description }}"
                                        data-status="{{ $brand->status }}"
                                        title="Sửa">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.brands.destroy', $brand->id) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa thương hiệu này?');">
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
                        <td colspan="8" class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                            <p class="text-muted">Không tìm thấy thương hiệu nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($brands->hasPages())
        <div class="admin-pagination">
            {{ $brands->links('vendor.pagination.custom') }}
        </div>
        @endif
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createBrandModal" tabindex="-1" aria-labelledby="createBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.brands.store') }}" method="POST" id="createBrandForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createBrandModalLabel">Thêm thương hiệu mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên thương hiệu *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-check">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" name="status" class="form-check-input" id="status" value="1" checked>
                        <label class="form-check-label" for="status">Hiển thị</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editBrandModal" tabindex="-1" aria-labelledby="editBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" id="editBrandForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editBrandModalLabel">Sửa thương hiệu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên thương hiệu *</label>
                        <input type="text" name="name" id="editBrandName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" id="editBrandDescription" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-check">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" name="status" class="form-check-input" id="editBrandStatus" value="1">
                        <label class="form-check-label" for="editBrandStatus">Hiển thị</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle edit button click - populate modal with brand data
    document.querySelectorAll('.edit-brand-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const brandId = this.getAttribute('data-id');
            const brandName = this.getAttribute('data-name');
            const brandDescription = this.getAttribute('data-description') || '';
            const brandStatus = this.getAttribute('data-status') === '1';
            
            // Update form action
            const editForm = document.getElementById('editBrandForm');
            editForm.action = '{{ route("admin.brands.update", ":id") }}'.replace(':id', brandId);
            
            // Populate form fields
            document.getElementById('editBrandName').value = brandName;
            document.getElementById('editBrandDescription').value = brandDescription;
            document.getElementById('editBrandStatus').checked = brandStatus;
        });
    });
    
    // Handle form submission
    document.querySelectorAll('#editBrandForm, #createBrandForm').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';
            }
        });
    });
    
    // Reset form when modal is closed
    const editModal = document.getElementById('editBrandModal');
    if (editModal) {
        editModal.addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('editBrandForm');
            if (form) {
                form.reset();
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Cập nhật';
                }
            }
        });
    }
    
    const createModal = document.getElementById('createBrandModal');
    if (createModal) {
        createModal.addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('createBrandForm');
            if (form) {
                form.reset();
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Thêm';
                }
                // Reset checkbox to checked
                const statusCheckbox = form.querySelector('input[name="status"]');
                if (statusCheckbox && statusCheckbox.type === 'checkbox') {
                    statusCheckbox.checked = true;
                }
            }
        });
    }
});
</script>
@endpush
@endsection

