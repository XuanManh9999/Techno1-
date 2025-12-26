<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Techno1 - Trang thiết bị điện tử')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <i class="bi bi-lightning-charge-fill me-2" style="font-size: 1.5rem;"></i>
                <span>Techno1</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house-door me-1"></i> Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                            <i class="bi bi-grid me-1"></i> Sản phẩm
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link cart-badge position-relative" href="{{ route('cart.index') }}" data-count="{{ auth()->user()->cartItems->sum('quantity') ?? 0 }}">
                                <i class="bi bi-cart3"></i> <span class="d-none d-md-inline">Giỏ hàng</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                                <i class="bi bi-receipt me-1"></i> <span class="d-none d-md-inline">Đơn hàng</span>
                            </a>
                        </li>
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i> <span class="d-none d-md-inline">Quản trị</span>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-2"></i>
                                <span class="d-none d-lg-inline">{{ auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg">
                                <li><h6 class="dropdown-header">{{ auth()->user()->name }}</h6></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.index') }}">
                                        <i class="bi bi-person me-2"></i> Thông tin cá nhân
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.change-password') }}">
                                        <i class="bi bi-key me-2"></i> Đổi mật khẩu
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('orders.index') }}">
                                        <i class="bi bi-receipt me-2"></i> Đơn hàng của tôi
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary text-white ms-2 px-3 {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i> Đăng ký
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm mt-4 mb-0" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <strong>Thành công!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm mt-4 mb-0" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Lỗi!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('status'))
                <div class="alert alert-info alert-dismissible fade show shadow-sm mt-4 mb-0" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        @yield('content')
    </main>

    <footer class="footer-professional">
        <div class="container">
            <div class="row g-4">
                <!-- Brand Column -->
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <div class="footer-brand">
                        <a href="{{ route('home') }}" class="d-flex align-items-center mb-3 text-decoration-none">
                            <i class="bi bi-lightning-charge-fill footer-logo-icon"></i>
                            <span class="footer-logo-text">Techno1</span>
                        </a>
                        <p class="footer-description">
                            Chuyên cung cấp trang thiết bị điện tử chất lượng cao với giá cả hợp lý. 
                            Cam kết mang đến trải nghiệm mua sắm tốt nhất cho khách hàng.
                        </p>
                        <div class="footer-social">
                            <a href="#" class="social-link" aria-label="Facebook" title="Facebook">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="Instagram" title="Instagram">
                                <i class="bi bi-instagram"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="YouTube" title="YouTube">
                                <i class="bi bi-youtube"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="Zalo" title="Zalo">
                                <i class="bi bi-chat-dots"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Links Column -->
                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                    <h5 class="footer-title">Liên kết nhanh</h5>
                    <ul class="footer-links">
                        <li>
                            <a href="{{ route('home') }}">
                                <i class="bi bi-chevron-right"></i> Trang chủ
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.index') }}">
                                <i class="bi bi-chevron-right"></i> Sản phẩm
                            </a>
                        </li>
                        @auth
                        <li>
                            <a href="{{ route('cart.index') }}">
                                <i class="bi bi-chevron-right"></i> Giỏ hàng
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('orders.index') }}">
                                <i class="bi bi-chevron-right"></i> Đơn hàng
                            </a>
                        </li>
                        @endauth
                    </ul>
                </div>

                <!-- Support Column -->
                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                    <h5 class="footer-title">Hỗ trợ</h5>
                    <ul class="footer-links">
                        <li>
                            <a href="#">
                                <i class="bi bi-chevron-right"></i> Về chúng tôi
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="bi bi-chevron-right"></i> Chính sách bảo hành
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="bi bi-chevron-right"></i> Chính sách đổi trả
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="bi bi-chevron-right"></i> Hướng dẫn mua hàng
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="bi bi-chevron-right"></i> Câu hỏi thường gặp
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Column -->
                <div class="col-lg-4 col-md-6">
                    <h5 class="footer-title">Liên hệ với chúng tôi</h5>
                    <ul class="footer-contact">
                        <li>
                            <i class="bi bi-geo-alt-fill"></i>
                            <div>
                                <strong>Địa chỉ:</strong><br>
                                123 Đường ABC, Quận XYZ, TP. Hồ Chí Minh
                            </div>
                        </li>
                        <li>
                            <i class="bi bi-envelope-fill"></i>
                            <div>
                                <strong>Email:</strong><br>
                                <a href="mailto:info@techno1.com">info@techno1.com</a>
                            </div>
                        </li>
                        <li>
                            <i class="bi bi-telephone-fill"></i>
                            <div>
                                <strong>Hotline:</strong><br>
                                <a href="tel:0123456789">0123 456 789</a>
                            </div>
                        </li>
                        <li>
                            <i class="bi bi-clock-fill"></i>
                            <div>
                                <strong>Giờ làm việc:</strong><br>
                                Thứ 2 - Chủ nhật: 8:00 - 22:00
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Newsletter Section -->
            <div class="footer-newsletter">
                <div class="row align-items-center">
                    <div class="col-lg-4 mb-3 mb-lg-0">
                        <h5 class="mb-0">
                            <i class="bi bi-envelope-paper me-2"></i>
                            Đăng ký nhận tin
                        </h5>
                        <p class="mb-0 small">Nhận thông tin khuyến mãi và sản phẩm mới</p>
                    </div>
                    <div class="col-lg-8">
                        <form class="newsletter-form" action="#" method="POST">
                            <div class="input-group">
                                <input type="email" class="form-control newsletter-input" placeholder="Nhập email của bạn..." required>
                                <button class="btn newsletter-btn" type="submit">
                                    <i class="bi bi-send-fill me-1"></i> Đăng ký
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <hr class="footer-divider">

            <!-- Copyright -->
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <p class="mb-0">
                            &copy; {{ date('Y') }} <strong>Techno1</strong>. Tất cả quyền được bảo lưu.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="footer-payment">
                            <span class="me-2">Chấp nhận thanh toán:</span>
                            <i class="bi bi-credit-card-2-front me-2" title="Thẻ tín dụng"></i>
                            <i class="bi bi-wallet2 me-2" title="Ví điện tử"></i>
                            <i class="bi bi-bank me-2" title="Chuyển khoản"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

