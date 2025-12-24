@extends('layouts.app')

@section('title', 'Thanh toán - Techno1')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Giỏ hàng</a></li>
        <li class="breadcrumb-item active">Thanh toán</li>
    </ol>
</nav>

<div class="d-flex align-items-center mb-4">
    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h2 class="mb-0">
        <i class="bi bi-credit-card me-2"></i>Thanh toán
    </h2>
</div>

@if($cartItems->count() > 0)
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-truck me-2"></i>Thông tin giao hàng
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person me-1"></i>Họ và tên *
                            </label>
                            <input type="text" 
                                   class="form-control @error('shipping_name') is-invalid @enderror" 
                                   name="shipping_name" 
                                   id="shipping_name"
                                   value="{{ old('shipping_name', auth()->user()->name) }}" 
                                   required>
                            @error('shipping_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-telephone me-1"></i>Số điện thoại *
                            </label>
                            <input type="text" 
                                   class="form-control @error('shipping_phone') is-invalid @enderror" 
                                   name="shipping_phone" 
                                   id="shipping_phone"
                                   value="{{ old('shipping_phone', auth()->user()->phone) }}" 
                                   required>
                            @error('shipping_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address Selection -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-geo-alt-fill me-1"></i>Tỉnh/Thành phố *
                            </label>
                            <select class="form-select @error('province_id') is-invalid @enderror" 
                                    name="province_id" 
                                    id="province_id" 
                                    required>
                                <option value="">-- Chọn Tỉnh/Thành phố --</option>
                            </select>
                            @error('province_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="spinner-border spinner-border-sm text-primary d-none mt-2" id="provinceSpinner" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-geo-alt me-1"></i>Quận/Huyện *
                            </label>
                            <select class="form-select @error('district_id') is-invalid @enderror" 
                                    name="district_id" 
                                    id="district_id" 
                                    required
                                    disabled>
                                <option value="">-- Chọn Quận/Huyện --</option>
                            </select>
                            @error('district_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="spinner-border spinner-border-sm text-primary d-none mt-2" id="districtSpinner" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-geo me-1"></i>Phường/Xã *
                            </label>
                            <select class="form-select @error('ward_id') is-invalid @enderror" 
                                    name="ward_id" 
                                    id="ward_id" 
                                    required
                                    disabled>
                                <option value="">-- Chọn Phường/Xã --</option>
                            </select>
                            @error('ward_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="spinner-border spinner-border-sm text-primary d-none mt-2" id="wardSpinner" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-house-door me-1"></i>Địa chỉ chi tiết (Số nhà, tên đường) *
                            </label>
                            <input type="text" 
                                   class="form-control @error('address_detail') is-invalid @enderror" 
                                   name="address_detail" 
                                   id="address_detail"
                                   placeholder="Ví dụ: 123 Đường ABC"
                                   value="{{ old('address_detail') }}" 
                                   required>
                            @error('address_detail')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="useFullAddress" checked>
                                <label class="form-check-label" for="useFullAddress">
                                    Tự động điền địa chỉ đầy đủ vào ô bên dưới
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-geo-alt me-1"></i>Địa chỉ giao hàng đầy đủ *
                            </label>
                            <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                                      name="shipping_address" 
                                      id="shipping_address"
                                      rows="3" 
                                      required>{{ old('shipping_address', auth()->user()->address) }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>Địa chỉ sẽ được tự động cập nhật khi bạn chọn Tỉnh/Thành phố, Quận/Huyện, Phường/Xã và nhập địa chỉ chi tiết.
                            </small>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-sticky me-1"></i>Ghi chú (tùy chọn)
                            </label>
                            <textarea class="form-control" 
                                      name="notes" 
                                      id="notes"
                                      rows="2" 
                                      placeholder="Ghi chú cho người giao hàng...">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Payment Method Selection -->
                        <div class="col-12">
                            <label class="form-label fw-semibold mb-3">
                                <i class="bi bi-credit-card me-1"></i>Phương thức thanh toán *
                            </label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="card payment-method-card h-100">
                                        <div class="card-body text-center p-4">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="radio" 
                                                       name="payment_method" 
                                                       id="payment_vnpay" 
                                                       value="VNPAY" 
                                                       {{ old('payment_method', 'VNPAY') === 'VNPAY' ? 'checked' : '' }}
                                                       required>
                                                <label class="form-check-label w-100" for="payment_vnpay">
                                                    <div class="payment-icon mb-2">
                                                        <i class="bi bi-credit-card-2-front display-4 text-primary"></i>
                                                    </div>
                                                    <h6 class="fw-bold">Thanh toán VNPAY</h6>
                                                    <small class="text-muted d-block">Thanh toán trực tuyến qua cổng VNPAY</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card payment-method-card h-100">
                                        <div class="card-body text-center p-4">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="radio" 
                                                       name="payment_method" 
                                                       id="payment_cod" 
                                                       value="COD"
                                                       {{ old('payment_method') === 'COD' ? 'checked' : '' }}
                                                       required>
                                                <label class="form-check-label w-100" for="payment_cod">
                                                    <div class="payment-icon mb-2">
                                                        <i class="bi bi-cash-coin display-4 text-success"></i>
                                                    </div>
                                                    <h6 class="fw-bold">Thanh toán khi nhận hàng</h6>
                                                    <small class="text-muted d-block">Thanh toán bằng tiền mặt khi nhận hàng</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('payment_method')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-lg w-100" id="submitBtn">
                            <i class="bi bi-check-circle me-2"></i>Xác nhận đặt hàng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-lg border-0 sticky-top" style="top: 100px;">
            <div class="card-header bg-gradient text-white">
                <h5 class="mb-0">
                    <i class="bi bi-receipt me-2"></i>Đơn hàng của bạn
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    @foreach($cartItems as $item)
                    <div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $item->product_name }}</h6>
                            @if($item->variant)
                            <small class="text-info d-block mb-1">
                                <i class="bi bi-tag me-1"></i>{{ $item->variant->attributes_string }}
                            </small>
                            @endif
                            <small class="text-muted">x{{ $item->quantity }}</small>
                        </div>
                        <strong class="text-danger ms-2">{{ number_format($item->subtotal) }}đ</strong>
                    </div>
                    @endforeach
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Tạm tính:</span>
                    <strong>{{ number_format($total) }}đ</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Phí vận chuyển:</span>
                    <strong class="text-success">Miễn phí</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-bold fs-5">Tổng cộng:</span>
                    <strong class="text-danger fs-4">{{ number_format($total) }}đ</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="card shadow-sm border-0">
    <div class="card-body text-center py-5">
        <i class="bi bi-cart-x display-1 text-muted d-block mb-3"></i>
        <h3 class="mb-3">Giỏ hàng trống</h3>
        <p class="text-muted mb-4">Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-arrow-right me-2"></i>Tiếp tục mua sắm
        </a>
    </div>
</div>
@endif

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
