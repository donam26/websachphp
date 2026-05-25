<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Trang chủ') - BookStore | Nhà sách trực tuyến</title>
    <meta name="description" content="BookStore - Nhà sách trực tuyến hàng đầu Việt Nam với hàng ngàn đầu sách hay, giá tốt, giao hàng nhanh chóng.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bs-primary: #c92127;
            --primary: #c92127;
            --primary-dark: #a51a1f;
            --primary-light: #ff4d57;
            --accent: #fdd835;
            --secondary: #2a5298;
            --success: #1ca672;
            --warning: #ff9800;
            --danger: #e53935;
            --text-dark: #1a1a1a;
            --text-muted: #6b7280;
            --bg-soft: #f4f6f8;
            --bg-card: #ffffff;
            --border-light: #e5e7eb;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            color: var(--text-dark);
            background: var(--bg-soft);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.5;
        }

        a { text-decoration: none; }
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }
        .btn-outline-primary:hover {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        .text-primary { color: var(--primary) !important; }
        .bg-primary { background-color: var(--primary) !important; }

        /* ===== Top announcement bar ===== */
        .topbar-announce {
            background: linear-gradient(90deg, var(--primary-dark), var(--primary));
            color: #fff;
            font-size: 12.5px;
            padding: 6px 0;
        }
        .topbar-announce a { color: #ffe082; font-weight: 600; }
        .topbar-announce .info-list a { color: #fff; opacity: .85; margin-right: 12px; }
        .topbar-announce .info-list a:hover { opacity: 1; }

        /* ===== Main header ===== */
        .main-header {
            background: var(--primary);
            color: #fff;
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: var(--shadow-md);
        }
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff !important;
            font-weight: 800;
            font-size: 24px;
            letter-spacing: -.5px;
        }
        .brand-logo .brand-icon {
            width: 42px; height: 42px;
            background: #fff;
            color: var(--primary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
        }
        .brand-logo small {
            display: block;
            font-size: 11px;
            font-weight: 500;
            opacity: .85;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .search-bar {
            background: #fff;
            border-radius: 4px;
            display: flex;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }
        .search-bar input {
            border: none;
            padding: 10px 16px;
            flex: 1;
            font-size: 14px;
            outline: none;
        }
        .search-bar button {
            border: none;
            background: var(--accent);
            color: var(--text-dark);
            padding: 0 22px;
            font-weight: 600;
            transition: .2s;
            display: flex; align-items: center; gap: 6px;
        }
        .search-bar button:hover { background: #fbc02d; }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 6px;
            justify-content: flex-end;
        }
        .header-action {
            color: rgba(255,255,255,.95);
            padding: 8px 12px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            position: relative;
            transition: .2s;
        }
        .header-action:hover {
            background: rgba(255,255,255,.12);
            color: #fff;
        }
        .header-action i { font-size: 20px; }
        .header-action .label {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }
        .header-action .label small { font-size: 11px; opacity: .85; }
        .header-action .label strong { font-weight: 600; }

        .cart-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            background: var(--accent);
            color: var(--text-dark);
            font-size: 10px;
            font-weight: 700;
            min-width: 18px;
            height: 18px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
        }

        /* ===== Category nav ===== */
        .nav-categories {
            background: #fff;
            border-bottom: 1px solid var(--border-light);
            padding: 0;
            position: sticky;
            top: 70px;
            z-index: 1020;
        }
        .nav-categories .nav-list {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            padding: 8px 0;
            margin: 0;
            list-style: none;
            overflow-x: auto;
        }
        .nav-categories .nav-list a {
            color: var(--text-dark);
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
            transition: .2s;
        }
        .nav-categories .nav-list a:hover,
        .nav-categories .nav-list a.active {
            background: rgba(201, 33, 39, .08);
            color: var(--primary);
        }
        .nav-categories .nav-list a i { margin-right: 5px; }

        /* ===== Main content ===== */
        main {
            flex: 1;
            padding: 24px 0 48px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            background: var(--bg-card);
        }
        .card-hover { transition: all .25s ease; }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .alert {
            border: none;
            border-radius: 10px;
            padding: 14px 18px;
            font-weight: 500;
        }
        .alert-success { background: rgba(28,166,114,.12); color: #0e7c54; }
        .alert-danger { background: rgba(229,57,53,.12); color: #b71c1c; }

        /* ===== Section title ===== */
        .section-title {
            position: relative;
            font-weight: 700;
            font-size: 22px;
            color: var(--text-dark);
            margin: 0;
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--primary);
        }

        /* ===== Product card (shared) ===== */
        .product-card {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid var(--border-light);
            transition: all .2s ease;
        }
        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-light);
        }
        .product-card .product-thumb {
            position: relative;
            padding-top: 130%;
            overflow: hidden;
            background: #fafafa;
        }
        .product-card .product-thumb img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .35s ease;
        }
        .product-card:hover .product-thumb img { transform: scale(1.06); }
        .product-card .product-discount {
            position: absolute;
            top: 8px;
            left: 8px;
            background: var(--primary);
            color: #fff;
            font-weight: 700;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 4px;
        }
        .product-card .product-body {
            padding: 12px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .product-card .product-title {
            font-size: 13.5px;
            font-weight: 500;
            color: var(--text-dark);
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 2.8em;
            margin-bottom: 6px;
        }
        .product-card .product-title:hover { color: var(--primary); }
        .product-card .product-author {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }
        .product-card .product-price {
            color: var(--primary);
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 4px;
        }
        .product-card .product-old-price {
            font-size: 12px;
            color: var(--text-muted);
            text-decoration: line-through;
        }
        .product-card .product-actions {
            margin-top: auto;
            padding-top: 10px;
        }
        .btn-add-cart {
            width: 100%;
            background: rgba(201,33,39,.1);
            color: var(--primary);
            border: 1px solid rgba(201,33,39,.2);
            border-radius: 6px;
            padding: 7px;
            font-weight: 600;
            font-size: 13px;
            transition: .2s;
        }
        .btn-add-cart:hover {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }
        .btn-add-cart:disabled { opacity: .5; cursor: not-allowed; }

        /* ===== Footer ===== */
        .footer {
            background: #2a2a2a;
            color: #d4d4d4;
            padding: 40px 0 0;
            margin-top: 40px;
        }
        .footer h6 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 16px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .footer a {
            color: #d4d4d4;
            display: block;
            padding: 4px 0;
            font-size: 13.5px;
            transition: .2s;
        }
        .footer a:hover { color: var(--accent); padding-left: 4px; }
        .footer .payment-icons img {
            background: #fff;
            border-radius: 4px;
            padding: 4px;
            margin: 4px 4px 0 0;
            height: 28px;
        }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.1);
            padding: 16px 0;
            margin-top: 32px;
            text-align: center;
            font-size: 12.5px;
            color: #999;
        }

        /* ===== Responsive ===== */
        @media (max-width: 992px) {
            .brand-logo small { display: none; }
            .header-action .label { display: none; }
            .nav-categories { top: 0; position: static; }
        }
        @media (max-width: 768px) {
            .main-header { position: static; padding: 10px 0; }
            .search-bar { margin-top: 8px; }
        }

        /* Pagination */
        .pagination { margin: 0; }
        .page-link {
            color: var(--text-dark);
            border-color: var(--border-light);
            padding: 8px 14px;
        }
        .page-link:hover { background: var(--bg-soft); color: var(--primary); }
        .page-item.active .page-link {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        /* Form controls */
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 .2rem rgba(201,33,39,.15);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 64px 20px;
            color: var(--text-muted);
        }
        .empty-state i { font-size: 64px; color: #cbd5e1; margin-bottom: 16px; display: block; }
        .empty-state h5 { color: var(--text-dark); margin-bottom: 8px; }
    </style>
    @stack('styles')
</head>

<body>
    {{-- Top announce bar --}}
    <div class="topbar-announce d-none d-md-block">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-truck me-1"></i> Freeship cho đơn từ 250.000đ &nbsp;|&nbsp;
                <i class="bi bi-telephone me-1"></i> Hotline: <a href="tel:1900636467">1900 6364 67</a>
            </div>
            <div class="info-list">
                <a href="#"><i class="bi bi-question-circle me-1"></i>Trợ giúp</a>
                <a href="#"><i class="bi bi-shop-window me-1"></i>Hệ thống cửa hàng</a>
            </div>
        </div>
    </div>

    {{-- Main header --}}
    <header class="main-header">
        <div class="container">
            <div class="row align-items-center g-3">
                <div class="col-lg-3 col-md-4 col-6">
                    <a href="{{ route('home') }}" class="brand-logo">
                        <span class="brand-icon"><i class="bi bi-book-half"></i></span>
                        <span>
                            BookStore
                            <small>Nhà sách online</small>
                        </span>
                    </a>
                </div>
                <div class="col-lg-6 col-12 order-lg-2 order-3">
                    <form class="search-bar" action="{{ route('books.index') }}" method="GET">
                        <input type="text" name="search" placeholder="Tìm kiếm sách, tác giả, danh mục..." value="{{ request('search') }}">
                        <button type="submit"><i class="bi bi-search"></i> <span class="d-none d-md-inline">Tìm kiếm</span></button>
                    </form>
                </div>
                <div class="col-lg-3 col-md-8 col-6 order-lg-3 order-2">
                    <div class="header-actions">
                        @auth
                            <div class="dropdown">
                                <a class="header-action dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle"></i>
                                    <span class="label"><small>Tài khoản</small><strong>{{ Str::limit(Auth::user()->full_name ?? Auth::user()->username, 10) }}</strong></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                    <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>Thông tin cá nhân</a></li>
                                    <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="bi bi-receipt me-2"></i>Đơn hàng của tôi</a></li>
                                    @if(Auth::user()->role === 'admin')
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Quản trị</a></li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">@csrf
                                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                            <a href="{{ route('cart.index') }}" class="header-action">
                                <i class="bi bi-cart3"></i>
                                <span class="label"><small>Giỏ hàng</small><strong>{{ number_format(($cartCount ?? 0)) }} sp</strong></span>
                                @if(($cartCount ?? 0) > 0)
                                    <span class="cart-badge">{{ $cartCount }}</span>
                                @endif
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="header-action">
                                <i class="bi bi-box-arrow-in-right"></i>
                                <span class="label"><small>Tài khoản</small><strong>Đăng nhập</strong></span>
                            </a>
                            <a href="{{ route('register') }}" class="header-action d-none d-md-flex">
                                <i class="bi bi-person-plus"></i>
                                <span class="label"><small>Đăng ký</small><strong>Tài khoản</strong></span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Category navigation --}}
    <nav class="nav-categories">
        <div class="container">
            <ul class="nav-list">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}"><i class="bi bi-house-door"></i>Trang chủ</a></li>
                <li><a href="{{ route('books.index') }}" class="{{ request()->routeIs('books.index') && !request('sort') ? 'active' : '' }}"><i class="bi bi-grid"></i>Tất cả sách</a></li>
                <li><a href="{{ route('books.index', ['sort' => 'best_seller']) }}"><i class="bi bi-fire"></i>Bán chạy</a></li>
                <li><a href="{{ route('books.index', ['sort' => 'discount']) }}"><i class="bi bi-tag"></i>Khuyến mãi</a></li>
                <li><a href="{{ route('books.index', ['sort' => 'latest']) }}"><i class="bi bi-stars"></i>Mới phát hành</a></li>
                @isset($globalCategories)
                    @foreach($globalCategories->take(6) as $cat)
                        <li><a href="{{ route('books.category', $cat->slug) }}">{{ $cat->name }}</a></li>
                    @endforeach
                @endisset
            </ul>
        </div>
    </nav>

    <main>
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <a href="{{ route('home') }}" class="brand-logo mb-3">
                        <span class="brand-icon"><i class="bi bi-book-half"></i></span>
                        <span>BookStore</span>
                    </a>
                    <p class="small text-secondary mt-2">Nhà sách trực tuyến hàng đầu Việt Nam — Hàng ngàn đầu sách hay, giá tốt, giao hàng nhanh chóng trên toàn quốc.</p>
                    <div class="mt-3">
                        <h6>Phương thức thanh toán</h6>
                        <div class="payment-icons">
                            <img src="https://cdn.jsdelivr.net/gh/aaronfagan/svg-credit-card-payment-icons/flat/visa.svg" alt="Visa">
                            <img src="https://cdn.jsdelivr.net/gh/aaronfagan/svg-credit-card-payment-icons/flat/mastercard.svg" alt="MasterCard">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2c/VNPAY_QR.svg/1200px-VNPAY_QR.svg.png" alt="VNPay" style="object-fit:contain;">
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <h6>Về BookStore</h6>
                    <a href="#">Giới thiệu</a>
                    <a href="#">Tuyển dụng</a>
                    <a href="#">Liên hệ</a>
                    <a href="#">Tin tức</a>
                </div>
                <div class="col-md-2 col-6">
                    <h6>Hỗ trợ KH</h6>
                    <a href="#">Chính sách đổi trả</a>
                    <a href="#">Chính sách bảo mật</a>
                    <a href="#">Phương thức thanh toán</a>
                    <a href="#">Câu hỏi thường gặp</a>
                </div>
                <div class="col-md-4">
                    <h6>Đăng ký nhận tin</h6>
                    <p class="small text-secondary">Nhận thông tin sách mới, khuyến mãi và sự kiện đặc biệt.</p>
                    <form class="d-flex gap-2 mt-2">
                        <input type="email" class="form-control form-control-sm" placeholder="Nhập email của bạn">
                        <button type="submit" class="btn btn-primary btn-sm">Đăng ký</button>
                    </form>
                    <h6 class="mt-4">Kết nối với chúng tôi</h6>
                    <div class="d-flex gap-2">
                        <a href="#" class="d-inline-flex align-items-center justify-content-center" style="width:36px;height:36px;background:#3b5998;border-radius:50%;color:#fff;"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="d-inline-flex align-items-center justify-content-center" style="width:36px;height:36px;background:#e1306c;border-radius:50%;color:#fff;"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="d-inline-flex align-items-center justify-content-center" style="width:36px;height:36px;background:#ff0000;border-radius:50%;color:#fff;"><i class="bi bi-youtube"></i></a>
                        <a href="#" class="d-inline-flex align-items-center justify-content-center" style="width:36px;height:36px;background:#1da1f2;border-radius:50%;color:#fff;"><i class="bi bi-twitter"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; {{ date('Y') }} BookStore — Bản quyền thuộc về BookStore. Made with <i class="bi bi-heart-fill text-danger"></i> by BookStore Team.
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
