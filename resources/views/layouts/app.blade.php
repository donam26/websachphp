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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <style>
        :root {
            /* ===== Brand: Indigo ===== */
            --bs-primary: #4f46e5;
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-darker: #3730a3;
            --primary-light: #818cf8;
            --primary-50: #eef2ff;
            --primary-100: #e0e7ff;

            /* ===== Accent: Amber (giá phụ, sale, sao) ===== */
            --accent: #f59e0b;
            --accent-dark: #d97706;
            --accent-soft: #fffbeb;

            /* ===== Semantic ===== */
            --success: #059669;
            --success-soft: #ecfdf5;
            --success-text: #047857;
            --danger: #e11d48;
            --danger-soft: #fff1f2;
            --danger-text: #be123c;
            --warning: #d97706;
            --info: #0284c7;

            /* ===== Neutral / surface ===== */
            --text: #0f172a;
            --text-muted: #64748b;
            --text-light: #94a3b8;
            --bg: #f1f5f9;
            --bg-soft: #f8fafc;
            --surface: #ffffff;
            --border: #e2e8f0;
            --border-strong: #cbd5e1;

            /* ===== Effects ===== */
            --shadow-xs: 0 1px 2px rgba(15, 23, 42, .06);
            --shadow-sm: 0 1px 3px rgba(15, 23, 42, .08), 0 1px 2px rgba(15, 23, 42, .04);
            --shadow-md: 0 6px 18px rgba(15, 23, 42, .08);
            --shadow-lg: 0 18px 40px rgba(15, 23, 42, .14);
            --shadow-primary: 0 8px 22px rgba(79, 70, 229, .28);
            --radius: 16px;
            --radius-sm: 10px;
            --radius-pill: 999px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            color: var(--text);
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6 { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; }

        a { text-decoration: none; color: var(--primary); }
        ::selection { background: var(--primary-100); color: var(--primary-darker); }

        /* ===== Buttons ===== */
        .btn { border-radius: var(--radius-sm); font-weight: 600; transition: all .2s ease; }
        .btn-lg { border-radius: 12px; }
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            box-shadow: var(--shadow-xs);
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            box-shadow: var(--shadow-primary);
            transform: translateY(-1px);
        }
        .btn-outline-primary { color: var(--primary); border-color: var(--border-strong); }
        .btn-outline-primary:hover { background-color: var(--primary); border-color: var(--primary); }
        .btn-warning {
            background-color: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }
        .btn-warning:hover { background-color: var(--accent-dark); border-color: var(--accent-dark); color: #fff; }
        .btn-light { background: #fff; border-color: transparent; color: var(--primary-dark); font-weight: 700; }
        .btn-light:hover { background: var(--primary-50); color: var(--primary-darker); }
        .btn-outline-danger { color: var(--danger); border-color: #fecdd3; }
        .btn-outline-danger:hover { background: var(--danger); border-color: var(--danger); }

        .text-primary { color: var(--primary) !important; }
        .bg-primary { background-color: var(--primary) !important; }
        .text-warning { color: var(--accent) !important; }
        .text-danger { color: var(--danger) !important; }
        .text-success { color: var(--success) !important; }
        .text-muted { color: var(--text-muted) !important; }
        .fw-black { font-weight: 800; }

        /* ===== Top announcement bar ===== */
        .topbar-announce {
            background: var(--primary-darker);
            color: rgba(255, 255, 255, .82);
            font-size: 12.5px;
            padding: 7px 0;
            letter-spacing: .1px;
        }
        .topbar-announce strong, .topbar-announce a { color: #fff; font-weight: 600; }
        .topbar-announce .accent { color: #fcd34d; }
        .topbar-announce .info-list a { opacity: .82; margin-left: 18px; font-weight: 500; }
        .topbar-announce .info-list a:hover { opacity: 1; }

        /* ===== Main header ===== */
        .main-header {
            background: var(--surface);
            color: var(--text);
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 1030;
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 11px;
            color: var(--text) !important;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
            font-size: 23px;
            letter-spacing: -.5px;
        }
        .brand-logo .brand-icon {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, var(--primary), var(--primary-darker));
            color: #fff;
            border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            box-shadow: var(--shadow-primary);
        }
        .brand-logo .brand-text { line-height: 1.05; }
        .brand-logo .brand-text b { color: var(--primary); }
        .brand-logo small {
            display: block;
            font-family: 'Inter', sans-serif;
            font-size: 10.5px;
            font-weight: 600;
            color: var(--text-light);
            letter-spacing: 2.5px;
            text-transform: uppercase;
        }

        .search-bar {
            background: var(--bg-soft);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-pill);
            display: flex;
            overflow: hidden;
            transition: all .2s ease;
        }
        .search-bar:focus-within {
            border-color: var(--primary-light);
            background: #fff;
            box-shadow: 0 0 0 4px var(--primary-50);
        }
        .search-bar input {
            border: none;
            background: transparent;
            padding: 11px 20px;
            flex: 1;
            font-size: 14px;
            outline: none;
            color: var(--text);
        }
        .search-bar button {
            border: none;
            background: var(--primary);
            color: #fff;
            padding: 0 24px;
            font-weight: 600;
            transition: .2s;
            display: flex; align-items: center; gap: 7px;
            margin: 4px;
            border-radius: var(--radius-pill);
        }
        .search-bar button:hover { background: var(--primary-dark); }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 4px;
            justify-content: flex-end;
        }
        .header-action {
            color: var(--text);
            padding: 8px 12px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 13px;
            position: relative;
            transition: .2s;
        }
        .header-action:hover { background: var(--primary-50); color: var(--primary-dark); }
        .header-action i { font-size: 21px; color: var(--primary); }
        .header-action .label {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
        }
        .header-action .label small { font-size: 11px; color: var(--text-muted); }
        .header-action .label strong { font-weight: 700; }

        .cart-badge {
            position: absolute;
            top: 0;
            right: 2px;
            background: var(--accent);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            min-width: 18px;
            height: 18px;
            border-radius: var(--radius-pill);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
            border: 2px solid #fff;
        }

        .dropdown-menu { border: 1px solid var(--border); border-radius: 14px; box-shadow: var(--shadow-lg); padding: 8px; }
        .dropdown-item { border-radius: 8px; padding: 9px 12px; font-size: 13.5px; font-weight: 500; }
        .dropdown-item:hover { background: var(--primary-50); color: var(--primary-dark); }
        .dropdown-item.text-danger:hover { background: var(--danger-soft); color: var(--danger-text); }

        /* ===== Category nav ===== */
        .nav-categories {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0;
            position: sticky;
            top: 73px;
            z-index: 1020;
        }
        .nav-categories .nav-list {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            padding: 9px 0;
            margin: 0;
            list-style: none;
            overflow-x: auto;
        }
        .nav-categories .nav-list a {
            color: var(--text);
            padding: 8px 15px;
            border-radius: var(--radius-pill);
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            transition: .2s;
        }
        .nav-categories .nav-list a:hover { background: var(--bg); color: var(--primary); }
        .nav-categories .nav-list a.active { background: var(--primary); color: #fff; }
        .nav-categories .nav-list a i { margin-right: 6px; }

        /* ===== Main content ===== */
        main { flex: 1; padding: 26px 0 56px; }

        .card {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            background: var(--surface);
        }
        .card-hover { transition: all .25s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }

        .alert { border: none; border-radius: 12px; padding: 14px 18px; font-weight: 500; }
        .alert-success { background: var(--success-soft); color: var(--success-text); }
        .alert-danger { background: var(--danger-soft); color: var(--danger-text); }
        .alert-light { background: var(--bg-soft); }

        /* ===== Badges (soft) ===== */
        .badge-soft-primary { background: var(--primary-50); color: var(--primary-dark); padding: 4px 10px; border-radius: var(--radius-pill); font-size: 11.5px; font-weight: 600; }
        .badge-soft-success { background: var(--success-soft); color: var(--success-text); padding: 4px 10px; border-radius: var(--radius-pill); font-size: 11.5px; font-weight: 600; }
        .badge-soft-danger { background: var(--danger-soft); color: var(--danger-text); padding: 4px 10px; border-radius: var(--radius-pill); font-size: 11.5px; font-weight: 600; }

        /* ===== Section title ===== */
        .section-title {
            position: relative;
            font-weight: 800;
            font-size: 22px;
            letter-spacing: -.4px;
            color: var(--text);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title .title-icon {
            width: 38px; height: 38px;
            border-radius: 11px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 19px;
            background: var(--primary-50);
            color: var(--primary);
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .section-link { color: var(--text-muted); font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
        .section-link:hover { color: var(--primary); }

        /* ===== Product card (shared) ===== */
        .product-card {
            background: var(--surface);
            border-radius: var(--radius);
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid var(--border);
            transition: all .22s ease;
        }
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-light);
        }
        .product-card .product-thumb {
            position: relative;
            padding-top: 132%;
            overflow: hidden;
            background: var(--bg-soft);
            display: block;
        }
        .product-card .product-thumb img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s ease;
        }
        .product-card:hover .product-thumb img { transform: scale(1.05); }
        .product-card .product-body {
            padding: 14px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .product-card .product-title {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text);
            line-height: 1.45;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 2.9em;
            margin-bottom: 6px;
        }
        .product-card .product-title:hover { color: var(--primary); }
        .product-card .product-author { font-size: 12px; color: var(--text-muted); margin-bottom: 8px; }
        .product-card .product-price { color: var(--primary); font-weight: 800; font-size: 17px; margin-bottom: 2px; letter-spacing: -.3px; }
        .product-card .product-actions { margin-top: auto; padding-top: 12px; }

        .btn-add-cart {
            width: 100%;
            background: var(--primary-50);
            color: var(--primary-dark);
            border: 1px solid var(--primary-100);
            border-radius: 10px;
            padding: 9px;
            font-weight: 600;
            font-size: 13px;
            transition: .2s;
        }
        .btn-add-cart:hover { background: var(--primary); color: #fff; border-color: var(--primary); box-shadow: var(--shadow-primary); }
        .btn-add-cart:disabled { opacity: .5; cursor: not-allowed; }

        /* ===== Footer ===== */
        .footer {
            background: #0f172a;
            color: #94a3b8;
            padding: 52px 0 0;
            margin-top: 48px;
        }
        .footer .brand-logo { color: #fff !important; }
        .footer .brand-logo small { color: #64748b; }
        .footer h6 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 18px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: .8px;
        }
        .footer a { color: #94a3b8; display: block; padding: 5px 0; font-size: 13.5px; transition: .2s; }
        .footer a:hover { color: #fff; padding-left: 5px; }
        .footer p { color: #94a3b8; }
        .footer .payment-icons img {
            background: #fff;
            border-radius: 6px;
            padding: 5px;
            margin: 4px 6px 0 0;
            height: 30px;
        }
        .footer .social-link {
            width: 38px; height: 38px;
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 11px;
            background: rgba(255, 255, 255, .08);
            color: #fff;
            transition: .2s;
        }
        .footer .social-link:hover { background: var(--primary); transform: translateY(-2px); }
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, .08);
            padding: 20px 0;
            margin-top: 40px;
            text-align: center;
            font-size: 12.5px;
            color: #64748b;
        }

        /* ===== Pagination ===== */
        .pagination { margin: 0; gap: 5px; }
        .page-link {
            color: var(--text);
            border: 1px solid var(--border);
            border-radius: 10px !important;
            padding: 8px 15px;
            font-weight: 600;
            font-size: 13.5px;
        }
        .page-link:hover { background: var(--primary-50); color: var(--primary); border-color: var(--primary-100); }
        .page-item.active .page-link { background-color: var(--primary); border-color: var(--primary); color: #fff; }

        /* ===== Forms ===== */
        .form-control, .form-select { border-radius: 10px; border-color: var(--border); padding: 10px 14px; font-size: 14px; }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 4px var(--primary-50);
        }
        .form-control-lg { border-radius: 12px; }

        /* ===== Empty state ===== */
        .empty-state { text-align: center; padding: 64px 20px; color: var(--text-muted); }
        .empty-state i { font-size: 60px; color: var(--border-strong); margin-bottom: 16px; display: block; }
        .empty-state h5 { color: var(--text); margin-bottom: 8px; font-weight: 700; }

        /* ===== Breadcrumb ===== */
        .breadcrumb { background: transparent; padding: 0; font-size: 13px; margin-bottom: 0; }
        .breadcrumb a { color: var(--text-muted); }
        .breadcrumb a:hover { color: var(--primary); }
        .breadcrumb-item.active { color: var(--text); font-weight: 500; }
        .breadcrumb-item + .breadcrumb-item::before { content: '›'; color: var(--text-light); }

        /* ===== Responsive ===== */
        @media (max-width: 992px) {
            .brand-logo small { display: none; }
            .header-action .label { display: none; }
            .nav-categories { top: 0; position: static; }
        }
        @media (max-width: 768px) {
            .main-header { position: static; padding: 12px 0; }
            .search-bar { margin-top: 10px; }
            .section-title { font-size: 19px; }
        }
    </style>
    @stack('styles')
</head>

<body>
    {{-- Top announce bar --}}
    <div class="topbar-announce d-none d-md-block">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-truck me-1 accent"></i> Freeship cho đơn từ <strong>250.000đ</strong>
                <span class="mx-2 opacity-50">|</span>
                <i class="bi bi-telephone me-1 accent"></i> Hotline: <a href="tel:1900636467" class="accent">1900 6364 67</a>
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
                        <span class="brand-text">
                            Book<b>Store</b>
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
                                <ul class="dropdown-menu dropdown-menu-end">
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
                            <a href="{{ route('cart.index') }}" class="header-action" id="cartLink">
                                <i class="bi bi-cart3"></i>
                                <span class="label"><small>Giỏ hàng</small><strong><span id="cartCountText">{{ number_format(($cartCount ?? 0)) }}</span> sp</strong></span>
                                <span class="cart-badge" id="cartBadge" style="{{ ($cartCount ?? 0) > 0 ? '' : 'display:none;' }}">{{ $cartCount ?? 0 }}</span>
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
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('warning') }}
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show">
                    <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
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
                        <span class="brand-text">Book<b style="color:var(--primary-light)">Store</b></span>
                    </a>
                    <p class="small mt-2">Nhà sách trực tuyến hàng đầu Việt Nam — Hàng ngàn đầu sách hay, giá tốt, giao hàng nhanh chóng trên toàn quốc.</p>
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
                    <p class="small">Nhận thông tin sách mới, khuyến mãi và sự kiện đặc biệt.</p>
                    <form class="d-flex gap-2 mt-2">
                        <input type="email" class="form-control form-control-sm" placeholder="Nhập email của bạn">
                        <button type="submit" class="btn btn-primary btn-sm text-nowrap">Đăng ký</button>
                    </form>
                    <h6 class="mt-4">Kết nối với chúng tôi</h6>
                    <div class="d-flex gap-2">
                        <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-youtube"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; {{ date('Y') }} BookStore — Bản quyền thuộc về BookStore. Made with <i class="bi bi-heart-fill" style="color:var(--danger)"></i> by BookStore Team.
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Toast notifications --}}
    <div id="toastContainer" style="position:fixed;top:20px;right:20px;z-index:1090;display:flex;flex-direction:column;gap:10px;"></div>
    <script>
        function showToast(message, type) {
            type = type || 'success';
            const container = document.getElementById('toastContainer');
            if (!container) return;
            const el = document.createElement('div');
            const bg = type === 'success' ? '#059669' : '#dc2626';
            const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill';
            el.style.cssText = 'min-width:280px;max-width:360px;background:' + bg + ';color:#fff;padding:12px 16px;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.18);display:flex;align-items:center;gap:10px;font-weight:500;opacity:0;transform:translateX(20px);transition:all .25s ease;';
            el.innerHTML = '<i class="bi ' + icon + '" style="font-size:18px;"></i><span style="flex:1;">' + message + '</span>';
            container.appendChild(el);
            requestAnimationFrame(function () { el.style.opacity = '1'; el.style.transform = 'translateX(0)'; });
            setTimeout(function () {
                el.style.opacity = '0';
                el.style.transform = 'translateX(20px)';
                setTimeout(function () { el.remove(); }, 300);
            }, 3000);
        }

        function updateCartCount(count) {
            if (typeof count === 'undefined' || count === null) return;
            const text = document.getElementById('cartCountText');
            const badge = document.getElementById('cartBadge');
            if (text) text.textContent = new Intl.NumberFormat('vi-VN').format(count);
            if (badge) {
                badge.textContent = count;
                badge.style.display = count > 0 ? '' : 'none';
            }
        }

        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (!(form instanceof HTMLFormElement)) return;
            const action = form.getAttribute('action') || '';
            if (!action.includes('/cart/add')) return;
            // "Mua ngay" giữ submit thường để chuyển sang giỏ hàng
            if (e.submitter && e.submitter.name === 'buy_now') return;

            e.preventDefault();
            const btn = e.submitter || form.querySelector('button[type="submit"]');
            const original = btn ? btn.innerHTML : '';
            if (btn) { btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>'; }

            fetch(action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                body: new FormData(form),
            })
            .then(async function (res) {
                const data = await res.json().catch(function () { return {}; });
                if (!res.ok || !data.success) {
                    showToast(data.message || 'Có lỗi xảy ra, vui lòng thử lại', 'error');
                    return;
                }
                showToast(data.message, 'success');
                updateCartCount(data.cartCount);
            })
            .catch(function () { showToast('Không thể kết nối máy chủ', 'error'); })
            .finally(function () { if (btn) { btn.disabled = false; btn.innerHTML = original; } });
        });
    </script>

    @stack('scripts')
</body>

</html>
