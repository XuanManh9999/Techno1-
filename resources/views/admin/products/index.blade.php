@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">
            <i class="bi bi-box-seam"></i>
            Quản lý sản phẩm
        </h1>
        <p class="admin-page-subtitle">Quản lý danh sách sản phẩm trong hệ thống</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn-admin btn-admin-primary">
        <i class="bi bi-plus-circle"></i>
        Thêm sản phẩm
    </a>
</div>

<!-- Search and Filter -->
<div class="admin-card mb-4">
    <div class="admin-card-body">
        <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="admin-form-label">Tìm kiếm</label>
                <input type="text" name="search" class="admin-form-control" 
                       value="{{ request('search') }}" 
                       placeholder="Tên, SKU, mô tả...">
            </div>
            <div class="col-md-2">
                <label class="admin-form-label">Danh mục</label>
                <select name="category_id" class="admin-form-control">
                    <option value="">Tất cả</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="admin-form-label">Thương hiệu</label>
                <select name="brand_id" class="admin-form-control">
                    <option value="">Tất cả</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="admin-form-label">Trạng thái</label>
                <select name="status" class="admin-form-control">
                    <option value="">Tất cả</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn-admin btn-admin-primary flex-fill">
                    <i class="bi bi-search"></i> Tìm kiếm
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn-admin btn-admin-secondary">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Products Table -->
<div class="admin-card">
    <div class="admin-card-header">
        <h2 class="admin-card-title">
            <i class="bi bi-list-ul"></i>
            Danh sách sản phẩm
            <span class="badge bg-primary ms-2">{{ $products->total() }}</span>
        </h2>
    </div>
    <div class="admin-card-body">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th style="width: 80px;">Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th style="width: 120px;">Danh mục</th>
                        <th style="width: 120px;">Thương hiệu</th>
                        <th style="width: 120px;">Giá</th>
                        <th style="width: 100px;">Tồn kho</th>
                        <th style="width: 100px;">Trạng thái</th>
                        <th style="width: 120px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td><strong>#{{ $product->id }}</strong></td>
                        <td>
                            <img src="{{ $product->image ?? 'https://via.placeholder.com/60' }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-thumbnail" 
                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                        </td>
                        <td>
                            <div>
                                <strong>{{ $product->name }}</strong>
                                @if($product->featured)
                                    <span class="admin-badge badge-warning ms-2">
                                        <i class="bi bi-star-fill"></i> Nổi bật
                                    </span>
                                @endif
                            </div>
                            <small class="text-muted">SKU: {{ $product->sku }}</small>
                        </td>
                        <td>
                            <span class="admin-badge badge-info">{{ $product->category->name }}</span>
                        </td>
                        <td>
                            @if($product->brand)
                                <span class="admin-badge badge-secondary">{{ $product->brand->name }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($product->sale_price)
                                <div>
                                    <span class="text-decoration-line-through text-muted small d-block">{{ number_format($product->price) }}₫</span>
                                    <span class="text-danger fw-bold">{{ number_format($product->sale_price) }}₫</span>
                                </div>
                            @else
                                <span class="fw-bold">{{ number_format($product->price) }}₫</span>
                            @endif
                        </td>
                        <td>
                            <span class="admin-badge {{ $product->stock_quantity > 0 ? 'badge-success' : 'badge-danger' }}">
                                {{ number_format($product->stock_quantity) }}
                            </span>
                        </td>
                        <td>
                            @if($product->status)
                                <span class="admin-badge badge-success">Hiển thị</span>
                            @else
                                <span class="admin-badge badge-secondary">Ẩn</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.products.edit', $product->id) }}" 
                                   class="btn-admin btn-admin-warning btn-admin-sm" 
                                   title="Sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-admin btn-admin-danger btn-admin-sm" title="Xóa">
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
                            <p class="text-muted mb-0">Không tìm thấy sản phẩm nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="admin-pagination">
            {{ $products->links('vendor.pagination.custom') }}
        </div>
        @endif
    </div>
</div>
@endsection
