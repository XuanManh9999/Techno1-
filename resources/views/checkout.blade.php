@extends('layouts.app')

@section('title', 'Thanh toán - Techno1')

@section('content')
<!-- Checkout Section -->
<section class="checkout-section">
    <div class="checkout-background">
        <div class="container">
            <!-- Breadcrumb -->
            <div class="breadcrumb-section mb-4">
                <div class="breadcrumb-background">
                    <div class="container">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb-modern">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Giỏ hàng</a></li>
                                <li class="breadcrumb-item active">Thanh toán</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Checkout Header -->
            <div class="checkout-header mb-5">
                <a href="{{ route('cart.index') }}" class="btn-back">
                    <i class="bi bi-arrow-left me-2"></i>Quay lại giỏ hàng
                </a>
                <div class="checkout-title-wrapper">
                    <h2 class="section-title">
                        <i class="bi bi-credit-card me-2"></i>Thanh toán
                    </h2>
                    <p class="section-subtitle mb-0">Vui lòng điền thông tin giao hàng để hoàn tất đơn hàng</p>
                </div>
            </div>

@if($cartItems->count() > 0)
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="checkout-form-card">
                        <div class="checkout-form-header">
                            <h5 class="checkout-form-title">
                                <i class="bi bi-truck me-2"></i>Thông tin giao hàng
                            </h5>
                        </div>
                        <div class="checkout-form-body">
                <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
                    @csrf

                            <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="bi bi-person me-1"></i>Họ và tên <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-wrapper">
                                                <span class="input-icon"><i class="bi bi-person"></i></span>
                                                <input type="text" 
                                                       class="form-control @error('shipping_name') is-invalid @enderror" 
                                                       name="shipping_name" 
                                                       id="shipping_name"
                                                       value="{{ old('shipping_name', auth()->user()->name) }}" 
                                                       required>
                                            </div>
                                            @error('shipping_name')
                                                <div class="error-message">
                                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="bi bi-telephone me-1"></i>Số điện thoại <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-wrapper">
                                                <span class="input-icon"><i class="bi bi-telephone"></i></span>
                                                <input type="text" 
                                                       class="form-control @error('shipping_phone') is-invalid @enderror" 
                                                       name="shipping_phone" 
                                                       id="shipping_phone"
                                                       value="{{ old('shipping_phone', auth()->user()->phone) }}" 
                                                       required>
                                            </div>
                                            @error('shipping_phone')
                                                <div class="error-message">
                                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Address Selection -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="bi bi-geo-alt-fill me-1"></i>Tỉnh/Thành phố <span class="text-danger">*</span>
                                            </label>
                                            <div class="select-wrapper">
                                                <select class="form-select @error('province_id') is-invalid @enderror" 
                                                        name="province_id" 
                                                        id="province_id" 
                                                        required>
                                                    <option value="">-- Chọn Tỉnh/Thành phố --</option>
                                                </select>
                                                <div class="spinner-wrapper">
                                                    <div class="spinner-border spinner-border-sm text-primary d-none" id="provinceSpinner" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('province_id')
                                                <div class="error-message">
                                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="bi bi-geo-alt me-1"></i>Quận/Huyện <span class="text-danger">*</span>
                                            </label>
                                            <div class="select-wrapper">
                                                <select class="form-select @error('district_id') is-invalid @enderror" 
                                                        name="district_id" 
                                                        id="district_id" 
                                                        required
                                                        disabled>
                                                    <option value="">-- Chọn Quận/Huyện --</option>
                                                </select>
                                                <div class="spinner-wrapper">
                                                    <div class="spinner-border spinner-border-sm text-primary d-none" id="districtSpinner" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('district_id')
                                                <div class="error-message">
                                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="bi bi-geo me-1"></i>Phường/Xã <span class="text-danger">*</span>
                                            </label>
                                            <div class="select-wrapper">
                                                <select class="form-select @error('ward_id') is-invalid @enderror" 
                                                        name="ward_id" 
                                                        id="ward_id" 
                                                        required
                                                        disabled>
                                                    <option value="">-- Chọn Phường/Xã --</option>
                                                </select>
                                                <div class="spinner-wrapper">
                                                    <div class="spinner-border spinner-border-sm text-primary d-none" id="wardSpinner" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('ward_id')
                                                <div class="error-message">
                                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="bi bi-house-door me-1"></i>Địa chỉ chi tiết (Số nhà, tên đường) <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-wrapper">
                                                <span class="input-icon"><i class="bi bi-house-door"></i></span>
                                                <input type="text" 
                                                       class="form-control @error('address_detail') is-invalid @enderror" 
                                                       name="address_detail" 
                                                       id="address_detail"
                                                       placeholder="Ví dụ: 123 Đường ABC"
                                                       value="{{ old('address_detail') }}" 
                                                       required>
                                            </div>
                                            @error('address_detail')
                                                <div class="error-message">
                                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check-custom">
                                            <input class="form-check-input" type="checkbox" id="useFullAddress" checked>
                                            <label class="form-check-label" for="useFullAddress">
                                                <span class="checkmark"></span>
                                                Tự động điền địa chỉ đầy đủ vào ô bên dưới
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="bi bi-geo-alt me-1"></i>Địa chỉ giao hàng đầy đủ <span class="text-danger">*</span>
                                            </label>
                                            <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                                                      name="shipping_address" 
                                                      id="shipping_address"
                                                      rows="3" 
                                                      required>{{ old('shipping_address', auth()->user()->address) }}</textarea>
                                            @error('shipping_address')
                                                <div class="error-message">
                                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                            <small class="form-hint">
                                                <i class="bi bi-info-circle me-1"></i>Địa chỉ sẽ được tự động cập nhật khi bạn chọn Tỉnh/Thành phố, Quận/Huyện, Phường/Xã và nhập địa chỉ chi tiết.
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="bi bi-sticky me-1"></i>Ghi chú (tùy chọn)
                                            </label>
                                            <textarea class="form-control" 
                                                      name="notes" 
                                                      id="notes"
                                                      rows="2" 
                                                      placeholder="Ghi chú cho người giao hàng...">{{ old('notes') }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Payment Method Selection -->
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label mb-3">
                                                <i class="bi bi-credit-card me-1"></i>Phương thức thanh toán <span class="text-danger">*</span>
                                            </label>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="payment-method-card-modern">
                                                        <div class="form-check-custom payment-radio">
                                                            <input class="form-check-input" 
                                                                   type="radio" 
                                                                   name="payment_method" 
                                                                   id="payment_vnpay" 
                                                                   value="VNPAY" 
                                                                   {{ old('payment_method', 'VNPAY') === 'VNPAY' ? 'checked' : '' }}
                                                                   required>
                                                            <label class="form-check-label w-100" for="payment_vnpay">
                                                                <div class="payment-method-content">
                                                                    <div class="payment-icon">
                                                                        <i class="bi bi-credit-card-2-front"></i>
                                                                    </div>
                                                                    <h6 class="payment-title">Thanh toán VNPAY</h6>
                                                                    <small class="payment-description">Thanh toán trực tuyến qua cổng VNPAY</small>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="payment-method-card-modern">
                                                        <div class="form-check-custom payment-radio">
                                                            <input class="form-check-input" 
                                                                   type="radio" 
                                                                   name="payment_method" 
                                                                   id="payment_cod" 
                                                                   value="COD"
                                                                   {{ old('payment_method') === 'COD' ? 'checked' : '' }}
                                                                   required>
                                                            <label class="form-check-label w-100" for="payment_cod">
                                                                <div class="payment-method-content">
                                                                    <div class="payment-icon payment-icon-cod">
                                                                        <i class="bi bi-cash-coin"></i>
                                                                    </div>
                                                                    <h6 class="payment-title">Thanh toán khi nhận hàng</h6>
                                                                    <small class="payment-description">Thanh toán bằng tiền mặt khi nhận hàng</small>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('payment_method')
                                                <div class="error-message mt-2">
                                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="checkout-form-footer">
                                    <button type="submit" class="btn-checkout-submit" id="submitBtn">
                                        <i class="bi bi-check-circle me-2"></i>Xác nhận đặt hàng
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="checkout-order-summary-card">
                        <div class="checkout-order-summary-header">
                            <h5 class="checkout-order-summary-title">
                                <i class="bi bi-receipt me-2"></i>Đơn hàng của bạn
                            </h5>
                        </div>
                        <div class="checkout-order-summary-body">
                            <div class="order-items-list">
                                @foreach($cartItems as $item)
                                <div class="order-item-modern">
                                    <div class="order-item-image-wrapper">
                                        <a href="{{ route('products.show', $item->product->slug) }}">
                                            @if($item->product_image ?? $item->product->image)
                                                <img src="{{ $item->product_image ?? $item->product->image }}" 
                                                     alt="{{ $item->product_name }}" 
                                                     class="order-item-image"
                                                     onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'80\' height=\'80\'%3E%3Crect width=\'80\' height=\'80\' fill=\'%23f1f5f9\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%2364748b\' font-family=\'Arial\' font-size=\'10\'%3ENo Image%3C/text%3E%3C/svg%3E';">
                                            @else
                                                <div class="order-item-image-placeholder">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            @endif
                                        </a>
                                    </div>
                                    <div class="order-item-content">
                                        <div class="order-item-info">
                                            <h6 class="order-item-name">
                                                <a href="{{ route('products.show', $item->product->slug) }}">{{ $item->product_name }}</a>
                                            </h6>
                                            @if($item->variant)
                                            <div class="order-item-variant">
                                                <i class="bi bi-tag me-1"></i>{{ $item->variant->attributes_string }}
                                            </div>
                                            @endif
                                            <div class="order-item-meta">
                                                <div class="order-item-quantity">
                                                    <i class="bi bi-box"></i>
                                                    <span>Số lượng: <strong>{{ $item->quantity }}</strong></span>
                                                </div>
                                                <div class="order-item-unit-price">
                                                    <i class="bi bi-currency-dollar"></i>
                                                    <span>Đơn giá: <strong>{{ number_format($item->product->final_price) }}₫</strong></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="order-item-price-wrapper">
                                            <div class="order-item-price-label">Thành tiền</div>
                                            <div class="order-item-price">{{ number_format($item->subtotal) }}₫</div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="order-summary-divider"></div>
                            <div class="summary-row">
                                <span class="summary-label">Tạm tính:</span>
                                <span class="summary-value">{{ number_format($total) }}₫</span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Phí vận chuyển:</span>
                                <span class="summary-value summary-value-success">
                                    <i class="bi bi-check-circle me-1"></i>Miễn phí
                                </span>
                            </div>
                            <div class="order-summary-divider"></div>
                            <div class="summary-row summary-row-total">
                                <span class="summary-label">Tổng cộng:</span>
                                <span class="summary-value summary-value-total">{{ number_format($total) }}₫</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@else
            <div class="empty-cart-card">
                <div class="empty-cart-content">
                    <div class="empty-cart-icon">
                        <i class="bi bi-cart-x"></i>
                    </div>
                    <h3 class="empty-cart-title">Giỏ hàng trống</h3>
                    <p class="empty-cart-text">Bạn chưa có sản phẩm nào trong giỏ hàng. Hãy bắt đầu mua sắm ngay!</p>
                    <a href="{{ route('products.index') }}" class="btn-shopping-now">
                        <i class="bi bi-arrow-right me-2"></i>Tiếp tục mua sắm
                    </a>
                </div>
            </div>
@endif
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const provinceSelect = document.getElementById('province_id');
    const districtSelect = document.getElementById('district_id');
    const wardSelect = document.getElementById('ward_id');
    const addressDetailInput = document.getElementById('address_detail');
    const shippingAddressTextarea = document.getElementById('shipping_address');
    const useFullAddressCheckbox = document.getElementById('useFullAddress');

    // Load provinces on page load
    loadProvinces();

    // Province change event
    provinceSelect.addEventListener('change', function() {
        const provinceId = this.value;
        if (provinceId) {
            loadDistricts(provinceId);
            districtSelect.disabled = false;
            districtSelect.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';
            wardSelect.disabled = true;
            wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
            updateFullAddress();
        } else {
            districtSelect.disabled = true;
            wardSelect.disabled = true;
        }
    });

    // District change event
    districtSelect.addEventListener('change', function() {
        const districtId = this.value;
        if (districtId) {
            loadWards(districtId);
            wardSelect.disabled = false;
            wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
            updateFullAddress();
        } else {
            wardSelect.disabled = true;
        }
    });

    // Ward change event
    wardSelect.addEventListener('change', function() {
        updateFullAddress();
    });

    // Address detail change event
    addressDetailInput.addEventListener('input', function() {
        if (useFullAddressCheckbox.checked) {
            updateFullAddress();
        }
    });

    // Checkbox change event
    useFullAddressCheckbox.addEventListener('change', function() {
        if (this.checked) {
            updateFullAddress();
        }
    });

    // Load provinces
    function loadProvinces() {
        const spinner = document.getElementById('provinceSpinner');
        spinner.classList.remove('d-none');
        provinceSelect.disabled = true;

        fetch('/api/address/provinces')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                provinceSelect.innerHTML = '<option value="">-- Chọn Tỉnh/Thành phố --</option>';
                if (data && data.length > 0) {
                    data.forEach(province => {
                        const option = document.createElement('option');
                        option.value = province.code || province.id;
                        option.textContent = province.name;
                        provinceSelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Không có dữ liệu';
                    provinceSelect.appendChild(option);
                }
                provinceSelect.disabled = false;
                spinner.classList.add('d-none');
            })
            .catch(error => {
                console.error('Error loading provinces:', error);
                spinner.classList.add('d-none');
                provinceSelect.disabled = false;
                provinceSelect.innerHTML = '<option value="">-- Lỗi tải dữ liệu --</option>';
            });
    }

    // Load districts
    function loadDistricts(provinceId) {
        const spinner = document.getElementById('districtSpinner');
        spinner.classList.remove('d-none');
        districtSelect.disabled = true;
        districtSelect.innerHTML = '<option value="">-- Đang tải... --</option>';

        fetch(`/api/address/districts/${provinceId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                districtSelect.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';
                if (data && data.length > 0) {
                    data.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.code || district.id;
                        option.textContent = district.name;
                        districtSelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Không có dữ liệu';
                    districtSelect.appendChild(option);
                }
                districtSelect.disabled = false;
                spinner.classList.add('d-none');
            })
            .catch(error => {
                console.error('Error loading districts:', error);
                spinner.classList.add('d-none');
                districtSelect.disabled = false;
                districtSelect.innerHTML = '<option value="">-- Lỗi tải dữ liệu --</option>';
            });
    }

    // Load wards
    function loadWards(districtId) {
        const spinner = document.getElementById('wardSpinner');
        spinner.classList.remove('d-none');
        wardSelect.disabled = true;
        wardSelect.innerHTML = '<option value="">-- Đang tải... --</option>';

        fetch(`/api/address/wards/${districtId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
                if (data && data.length > 0) {
                    data.forEach(ward => {
                        const option = document.createElement('option');
                        option.value = ward.code || ward.id;
                        option.textContent = ward.name;
                        wardSelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Không có dữ liệu';
                    wardSelect.appendChild(option);
                }
                wardSelect.disabled = false;
                spinner.classList.add('d-none');
            })
            .catch(error => {
                console.error('Error loading wards:', error);
                spinner.classList.add('d-none');
                wardSelect.disabled = false;
                wardSelect.innerHTML = '<option value="">-- Lỗi tải dữ liệu --</option>';
            });
    }

    // Update full address
    function updateFullAddress() {
        if (!useFullAddressCheckbox.checked) {
            return;
        }

        const addressParts = [];
        
        if (addressDetailInput.value.trim()) {
            addressParts.push(addressDetailInput.value.trim());
        }

        const selectedWard = wardSelect.options[wardSelect.selectedIndex];
        if (selectedWard && selectedWard.value) {
            addressParts.push(selectedWard.textContent);
        }

        const selectedDistrict = districtSelect.options[districtSelect.selectedIndex];
        if (selectedDistrict && selectedDistrict.value) {
            addressParts.push(selectedDistrict.textContent);
        }

        const selectedProvince = provinceSelect.options[provinceSelect.selectedIndex];
        if (selectedProvince && selectedProvince.value) {
            addressParts.push(selectedProvince.textContent);
        }

        shippingAddressTextarea.value = addressParts.join(', ');
    }

    // Form validation
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        if (!provinceSelect.value || !districtSelect.value || !wardSelect.value) {
            e.preventDefault();
            alert('Vui lòng chọn đầy đủ Tỉnh/Thành phố, Quận/Huyện và Phường/Xã');
            return false;
        }
    });
});
</script>
@endpush
@endsection
