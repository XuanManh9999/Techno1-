@extends('layouts.admin')

@section('title', 'Sửa sản phẩm')

@section('content')
<h2 class="mb-4">Sửa sản phẩm</h2>

<form action="{{ route('admin.products.update', $product->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Tên sản phẩm *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả ngắn</label>
                        <textarea class="form-control" name="short_description" rows="3">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả chi tiết</label>
                        <textarea class="form-control" name="description" id="description" rows="10">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Ảnh sản phẩm (URL)</label>
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   name="image" 
                                   id="product_image_url"
                                   value="{{ old('image', $product->image) }}" 
                                   placeholder="https://example.com/image.jpg">
                            <button type="button" class="btn btn-outline-secondary" id="previewImageBtn">
                                <i class="bi bi-eye"></i> Xem
                            </button>
                        </div>
                        <small class="text-muted d-block mt-1">
                            <i class="bi bi-info-circle"></i> Nhập URL ảnh hoặc để trống
                        </small>
                        @error('image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div id="imagePreview" class="mt-2">
                            @if($product->image)
                                <img id="previewImg" src="{{ $product->image }}" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px; display: block;">
                            @else
                                <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px; display: none;">
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Danh mục *</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" required>
                            <option value="">Chọn danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                                    {{ $category->name }}@if(!$category->status) <span class="text-muted">(Vô hiệu)</span>@endif
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Thương hiệu</label>
                        <select class="form-select" name="brand_id">
                            <option value="">Chọn thương hiệu</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">SKU *</label>
                        <input type="text" class="form-control @error('sku') is-invalid @enderror" name="sku" value="{{ old('sku', $product->sku) }}" required>
                        @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Giá *</label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price', $product->price) }}" min="0" step="0.01" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Giá khuyến mãi</label>
                        <input type="number" class="form-control" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" min="0" step="0.01">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Số lượng tồn kho *</label>
                        <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0" required>
                        @error('stock_quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" class="form-check-input" name="status" id="status" value="1" {{ old('status', $product->status) ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Hiển thị</label>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="hidden" name="featured" value="0">
                        <input type="checkbox" class="form-check-input" name="featured" id="featured" value="1" {{ old('featured', $product->featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="featured">Sản phẩm nổi bật</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Biến thể sản phẩm</h5>
                <small class="text-muted">Thêm các biến thể như màu sắc, kích thước cho sản phẩm (tùy chọn)</small>
            </div>
            <button type="button" class="btn btn-sm btn-primary" id="addVariantBtn">
                <i class="bi bi-plus-circle"></i> Thêm biến thể
            </button>
        </div>
        <div class="card-body">
            <div id="variantsContainer">
                @if(!$product->variants || $product->variants->count() == 0)
                    <div class="alert alert-info mb-0" id="noVariantsMessage">
                        <i class="bi bi-info-circle"></i> Chưa có biến thể nào. Nhấn nút "Thêm biến thể" ở trên để thêm biến thể đầu tiên.
                    </div>
                @endif
                @if($product->variants && $product->variants->count() > 0)
                    @foreach($product->variants as $variant)
                        <div class="card mb-3 variant-row" data-index="{{ $loop->index }}">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Biến thể #{{ $loop->index + 1 }}</h6>
                                <button type="button" class="btn btn-sm btn-danger remove-variant">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="variants[{{ $loop->index }}][id]" value="{{ $variant->id }}">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Màu sắc</label>
                                        <input type="text" class="form-control variant-color" name="variants[{{ $loop->index }}][attributes][Màu sắc]" placeholder="VD: Đỏ, Xanh, Đen..." value="{{ $variant->attributes['Màu sắc'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kích thước / Size</label>
                                        <input type="text" class="form-control variant-size" name="variants[{{ $loop->index }}][attributes][Kích thước]" placeholder="VD: S, M, L, XL hoặc 128GB, 256GB..." value="{{ $variant->attributes['Kích thước'] ?? '' }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">SKU</label>
                                        <input type="text" class="form-control" name="variants[{{ $loop->index }}][sku]" placeholder="Tự động tạo nếu để trống" value="{{ $variant->sku }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Giá (VNĐ)</label>
                                        <input type="number" class="form-control" name="variants[{{ $loop->index }}][price]" min="0" step="0.01" placeholder="Để trống dùng giá sản phẩm" value="{{ $variant->price }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Giá khuyến mãi (VNĐ)</label>
                                        <input type="number" class="form-control" name="variants[{{ $loop->index }}][sale_price]" min="0" step="0.01" value="{{ $variant->sale_price }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Số lượng tồn kho</label>
                                        <input type="number" class="form-control" name="variants[{{ $loop->index }}][stock_quantity]" min="0" value="{{ $variant->stock_quantity }}" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Ảnh biến thể (URL)</label>
                                        <input type="text" class="form-control" name="variants[{{ $loop->index }}][image]" placeholder="URL ảnh" value="{{ $variant->image }}">
                                    </div>
                                    <div class="col-md-4 mb-3 d-flex align-items-end">
                                        <div class="form-check mb-2">
                                            <input type="hidden" name="variants[{{ $loop->index }}][is_default]" value="0">
                                            <input type="checkbox" class="form-check-input variant-default" name="variants[{{ $loop->index }}][is_default]" value="1" {{ $variant->is_default ? 'checked' : '' }}>
                                            <label class="form-check-label">Mặc định</label>
                                        </div>
                                        <div class="form-check ms-3 mb-2">
                                            <input type="hidden" name="variants[{{ $loop->index }}][status]" value="0">
                                            <input type="checkbox" class="form-check-input" name="variants[{{ $loop->index }}][status]" value="1" {{ $variant->status ? 'checked' : '' }}>
                                            <label class="form-check-label">Kích hoạt</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a>
    </div>
</form>

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize TinyMCE for description
    tinymce.init({
        selector: '#description',
        height: 400,
        menubar: true,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | ' +
            'bold italic forecolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help | image | link | code',
        content_style: 'body { font-family: Arial, sans-serif; font-size: 14px }',
        language: 'vi',
        promotion: false,
    });

    // Image preview functionality
    const imageUrlInput = document.getElementById('product_image_url');
    const previewBtn = document.getElementById('previewImageBtn');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    if (previewBtn && imageUrlInput) {
        previewBtn.addEventListener('click', function() {
            const url = imageUrlInput.value.trim();
            if (url) {
                previewImg.src = url;
                if (imagePreview) {
                    imagePreview.style.display = 'block';
                }
            } else {
                if (imagePreview) {
                    imagePreview.style.display = 'none';
                }
            }
        });

        imageUrlInput.addEventListener('blur', function() {
            const url = this.value.trim();
            if (url) {
                previewImg.src = url;
                if (imagePreview) {
                    imagePreview.style.display = 'block';
                }
            } else {
                if (imagePreview && !imagePreview.querySelector('img').src) {
                    imagePreview.style.display = 'none';
                }
            }
        });
    }
    let variantIndex = {{ $product->variants ? $product->variants->count() : 0 }};
    const container = document.getElementById('variantsContainer');
    const noVariantsMessage = document.getElementById('noVariantsMessage');
    const addVariantBtn = document.getElementById('addVariantBtn');

    if (!addVariantBtn) {
        console.error('Add variant button not found');
        return;
    }

    addVariantBtn.addEventListener('click', function() {
        addVariantRow();
    });

    function addVariantRow(variantData = null) {
        // Ẩn thông báo "chưa có biến thể"
        if (noVariantsMessage) {
            noVariantsMessage.style.display = 'none';
        }
        
        const index = variantIndex++;
    
    const variantHtml = `
        <div class="card mb-3 variant-row" data-index="${index}">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Biến thể #${index + 1}</h6>
                <button type="button" class="btn btn-sm btn-danger remove-variant">
                    <i class="bi bi-trash"></i> Xóa
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Màu sắc</label>
                        <input type="text" class="form-control variant-color" name="variants[${index}][attributes][Màu sắc]" placeholder="VD: Đỏ, Xanh, Đen..." value="${variantData?.attributes?.['Màu sắc'] || ''}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kích thước / Size</label>
                        <input type="text" class="form-control variant-size" name="variants[${index}][attributes][Kích thước]" placeholder="VD: S, M, L, XL hoặc 128GB, 256GB..." value="${variantData?.attributes?.['Kích thước'] || ''}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">SKU</label>
                        <input type="text" class="form-control" name="variants[${index}][sku]" placeholder="Tự động tạo nếu để trống" value="${variantData?.sku || ''}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Giá (VNĐ)</label>
                        <input type="number" class="form-control" name="variants[${index}][price]" min="0" step="0.01" placeholder="Để trống dùng giá sản phẩm" value="${variantData?.price || ''}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Giá khuyến mãi (VNĐ)</label>
                        <input type="number" class="form-control" name="variants[${index}][sale_price]" min="0" step="0.01" value="${variantData?.sale_price || ''}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Số lượng tồn kho</label>
                        <input type="number" class="form-control" name="variants[${index}][stock_quantity]" min="0" value="${variantData?.stock_quantity || 0}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Ảnh biến thể (URL)</label>
                        <input type="text" class="form-control" name="variants[${index}][image]" placeholder="URL ảnh" value="${variantData?.image || ''}">
                    </div>
                    <div class="col-md-4 mb-3 d-flex align-items-end">
                        <div class="form-check mb-2">
                            <input type="hidden" name="variants[${index}][is_default]" value="0">
                            <input type="checkbox" class="form-check-input variant-default" name="variants[${index}][is_default]" value="1" ${variantData?.is_default ? 'checked' : ''}>
                            <label class="form-check-label">Mặc định</label>
                        </div>
                        <div class="form-check ms-3 mb-2">
                            <input type="hidden" name="variants[${index}][status]" value="0">
                            <input type="checkbox" class="form-check-input" name="variants[${index}][status]" value="1" ${variantData?.status !== false ? 'checked' : ''}>
                            <label class="form-check-label">Kích hoạt</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
        container.insertAdjacentHTML('beforeend', variantHtml);
        
        // Xử lý sự kiện xóa variant
        const removeBtn = container.querySelector(`.variant-row[data-index="${index}"] .remove-variant`);
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                const row = this.closest('.variant-row');
                if (row) {
                    row.remove();
                    updateVariantNumbers();
                    checkEmptyVariants();
                }
            });
        }
        
        // Xử lý checkbox mặc định - chỉ cho phép một variant là mặc định
        const defaultCheckbox = container.querySelector(`.variant-row[data-index="${index}"] .variant-default`);
        if (defaultCheckbox) {
            defaultCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    container.querySelectorAll('.variant-default').forEach(cb => {
                        if (cb !== this) cb.checked = false;
                    });
                }
            });
        }
    }

    function updateVariantNumbers() {
        document.querySelectorAll('.variant-row').forEach((row, index) => {
            const h6 = row.querySelector('h6');
            if (h6) {
                h6.textContent = `Biến thể #${index + 1}`;
            }
        });
    }

    function checkEmptyVariants() {
        const variantRows = container.querySelectorAll('.variant-row');
        if (variantRows.length === 0 && noVariantsMessage) {
            noVariantsMessage.style.display = 'block';
        }
    }

    // Xử lý sự kiện xóa variant khi click vào nút xóa (delegation)
    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-variant')) {
            const row = e.target.closest('.variant-row');
            if (row) {
                row.remove();
                updateVariantNumbers();
                checkEmptyVariants();
            }
        }
    });

    // Xử lý checkbox mặc định cho các variant hiện có
    document.querySelectorAll('.variant-default').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                document.querySelectorAll('.variant-default').forEach(cb => {
                    if (cb !== this) cb.checked = false;
                });
            }
        });
    });
});
</script>
@endpush
@endsection

