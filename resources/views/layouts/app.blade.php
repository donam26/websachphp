<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Fashion Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

    <style>
        :root {
            --fs-primary: #1a1a2e;
            --fs-accent: #c9a96e;
            --fs-accent-light: #e8d5b5;
            --fs-surface: #faf9f7;
            --fs-card: #ffffff;
            --fs-text: #2d2d2d;
            --fs-muted: #8c8c8c;
            --fs-border: #e8e6e3;
            --fs-danger: #c0392b;
            --fs-success: #27ae60;
            --fs-radius: 16px;
            --fs-radius-sm: 10px;
            --fs-shadow: 0 4px 24px rgba(0,0,0,0.06);
            --fs-shadow-hover: 0 12px 40px rgba(0,0,0,0.12);
            --fs-transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            color: var(--fs-text);
            background: var(--fs-surface);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Announcement Bar ── */
        .fs-announce {
            background: var(--fs-primary);
            color: rgba(255,255,255,0.85);
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 8px 0;
            text-align: center;
            font-weight: 500;
        }

        .fs-announce span { color: var(--fs-accent); font-weight: 600; }

        /* ── Navigation ── */
        .fs-nav {
            background: #fff;
            border-bottom: 1px solid var(--fs-border);
            position: sticky;
            top: 0;
            z-index: 1050;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            transition: var(--fs-transition);
        }

        .fs-nav.scrolled {
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
        }

        .fs-nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 0;
        }

        .fs-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--fs-primary);
            text-decoration: none;
            letter-spacing: 1px;
            transition: var(--fs-transition);
        }

        .fs-brand:hover { color: var(--fs-accent); }

        .fs-brand small {
            display: block;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.6rem;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: var(--fs-muted);
            font-weight: 500;
            margin-top: -2px;
        }

        .fs-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 0.25rem;
        }

        .fs-menu a {
            display: inline-block;
            padding: 0.5rem 1.1rem;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--fs-text);
            text-decoration: none;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border-radius: 100px;
            transition: var(--fs-transition);
            position: relative;
        }

        .fs-menu a::after {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 50%;
            width: 0;
            height: 1.5px;
            background: var(--fs-accent);
            transition: var(--fs-transition);
            transform: translateX(-50%);
        }

        .fs-menu a:hover { color: var(--fs-accent); }
        .fs-menu a:hover::after { width: 50%; }

        .fs-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .fs-search-toggle,
        .fs-icon-btn {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: none;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--fs-text);
            font-size: 1.15rem;
            transition: var(--fs-transition);
            cursor: pointer;
            text-decoration: none;
            position: relative;
        }

        .fs-search-toggle:hover,
        .fs-icon-btn:hover {
            background: var(--fs-surface);
            color: var(--fs-accent);
        }

        .fs-icon-btn .fs-badge {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 8px;
            height: 8px;
            background: var(--fs-accent);
            border-radius: 50%;
        }

        .fs-search-bar {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.4s ease, padding 0.4s ease;
            background: #fff;
            border-bottom: 1px solid var(--fs-border);
        }

        .fs-search-bar.open {
            max-height: 80px;
            padding: 1rem 0;
        }

        .fs-search-bar .form-control {
            border: none;
            border-bottom: 2px solid var(--fs-border);
            border-radius: 0;
            padding: 0.75rem 0;
            font-size: 1rem;
            background: transparent;
            transition: var(--fs-transition);
        }

        .fs-search-bar .form-control:focus {
            box-shadow: none;
            border-color: var(--fs-accent);
        }

        /* User dropdown */
        .fs-user-dropdown .dropdown-toggle::after { display: none; }

        .fs-user-dropdown .dropdown-menu {
            border: none;
            box-shadow: var(--fs-shadow-hover);
            border-radius: var(--fs-radius-sm);
            padding: 0.5rem;
            min-width: 220px;
            margin-top: 0.5rem;
            animation: dropFade 0.3s ease;
        }

        @keyframes dropFade {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fs-user-dropdown .dropdown-item {
            border-radius: 8px;
            padding: 0.65rem 1rem;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--fs-text);
            transition: var(--fs-transition);
        }

        .fs-user-dropdown .dropdown-item i { width: 20px; color: var(--fs-muted); margin-right: 8px; }
        .fs-user-dropdown .dropdown-item:hover { background: var(--fs-surface); color: var(--fs-accent); }
        .fs-user-dropdown .dropdown-item:hover i { color: var(--fs-accent); }

        /* ── Main ── */
        main {
            flex: 1;
            padding: 0;
        }

        /* ── Alerts ── */
        .fs-toast {
            position: fixed;
            top: 100px;
            right: 24px;
            z-index: 9999;
            min-width: 320px;
            max-width: 420px;
            animation: slideInRight 0.5s ease;
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(60px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .fs-toast .alert {
            border: none;
            border-radius: var(--fs-radius-sm);
            box-shadow: var(--fs-shadow-hover);
            padding: 1rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            backdrop-filter: blur(10px);
        }

        .fs-toast .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
        }

        .fs-toast .alert-danger {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
        }

        /* ── Footer ── */
        .fs-footer {
            background: var(--fs-primary);
            color: rgba(255,255,255,0.7);
            margin-top: auto;
        }

        .fs-footer-top {
            padding: 4rem 0 3rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .fs-footer-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 1rem;
        }

        .fs-footer-brand small {
            display: block;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.65rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--fs-accent);
            margin-top: 4px;
        }

        .fs-footer h6 {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--fs-accent);
            margin-bottom: 1.25rem;
        }

        .fs-footer ul { list-style: none; padding: 0; margin: 0; }

        .fs-footer ul li { margin-bottom: 0.6rem; }

        .fs-footer ul a {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 0.875rem;
            transition: var(--fs-transition);
        }

        .fs-footer ul a:hover {
            color: #fff;
            padding-left: 4px;
        }

        .fs-footer-contact li {
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .fs-footer-contact i { color: var(--fs-accent); font-size: 1rem; }

        .fs-footer-bottom {
            padding: 1.5rem 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
        }

        .fs-social a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            margin-left: 8px;
            transition: var(--fs-transition);
        }

        .fs-social a:hover {
            border-color: var(--fs-accent);
            color: var(--fs-accent);
            transform: translateY(-2px);
        }

        /* ── Global Enhancements ── */
        .fs-section {
            padding: 3rem 0;
        }

        .fs-section-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--fs-primary);
            margin-bottom: 0.5rem;
        }

        .fs-card {
            background: var(--fs-card);
            border: 1px solid var(--fs-border);
            border-radius: var(--fs-radius);
            box-shadow: var(--fs-shadow);
            transition: var(--fs-transition);
            overflow: hidden;
        }

        .fs-card:hover {
            box-shadow: var(--fs-shadow-hover);
            transform: translateY(-4px);
        }

        .fs-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0.7rem 1.75rem;
            border-radius: 100px;
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            text-decoration: none;
            border: 2px solid transparent;
            transition: var(--fs-transition);
            cursor: pointer;
        }

        .fs-btn-primary {
            background: var(--fs-primary);
            color: #fff;
            border-color: var(--fs-primary);
        }

        .fs-btn-primary:hover {
            background: transparent;
            color: var(--fs-primary);
        }

        .fs-btn-accent {
            background: var(--fs-accent);
            color: #fff;
            border-color: var(--fs-accent);
        }

        .fs-btn-accent:hover {
            background: transparent;
            color: var(--fs-accent);
        }

        .fs-btn-outline {
            background: transparent;
            color: var(--fs-text);
            border-color: var(--fs-border);
        }

        .fs-btn-outline:hover {
            border-color: var(--fs-primary);
            color: var(--fs-primary);
        }

        .fs-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.3rem 0.75rem;
            border-radius: 100px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .fs-badge-success { background: #e8f5e9; color: #2e7d32; }
        .fs-badge-danger { background: #fce4ec; color: #c62828; }
        .fs-badge-warning { background: #fff8e1; color: #f57f17; }
        .fs-badge-info { background: #e3f2fd; color: #1565c0; }
        .fs-badge-dark { background: var(--fs-primary); color: #fff; }
        .fs-badge-accent { background: var(--fs-accent-light); color: #8b6914; }

        /* Pagination */
        .pagination { gap: 4px; }

        .page-link {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50% !important;
            border: 1px solid var(--fs-border);
            color: var(--fs-text);
            font-size: 0.85rem;
            font-weight: 500;
            transition: var(--fs-transition);
        }

        .page-link:hover {
            background: var(--fs-primary);
            border-color: var(--fs-primary);
            color: #fff;
        }

        .page-item.active .page-link {
            background: var(--fs-primary);
            border-color: var(--fs-primary);
        }

        /* Form controls */
        .form-control, .form-select {
            border: 1.5px solid var(--fs-border);
            border-radius: var(--fs-radius-sm);
            padding: 0.7rem 1rem;
            font-size: 0.875rem;
            transition: var(--fs-transition);
            background: #fff;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--fs-accent);
            box-shadow: 0 0 0 3px rgba(201, 169, 110, 0.15);
        }

        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--fs-muted);
            margin-bottom: 0.4rem;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--fs-border); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--fs-muted); }

        /* Responsive */
        @media (max-width: 991px) {
            .fs-menu { display: none; }
            .fs-brand { font-size: 1.3rem; }
        }

        /* Page enter animation */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fs-animate {
            animation: fadeUp 0.6s ease forwards;
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Announcement Bar -->
    <div class="fs-announce">
        Mien phi van chuyen cho don hang tu <span>500.000d</span> &mdash; Doi tra mien phi trong 30 ngay
    </div>

    <!-- Navigation -->
    <nav class="fs-nav" id="mainNav">
        <div class="container">
            <div class="fs-nav-inner">
                <!-- Brand -->
                <a class="fs-brand" href="{{ route('home') }}">
                    FASHION
                    <small>Store</small>
                </a>

                <!-- Menu -->
                <ul class="fs-menu">
                    <li><a href="{{ route('books.index') }}">San pham</a></li>
                    <li><a href="{{ route('books.index', ['gender' => 'nam']) }}">Nam</a></li>
                    <li><a href="{{ route('books.index', ['gender' => 'nu']) }}">Nu</a></li>
                    <li><a href="{{ route('books.index', ['sort' => 'latest']) }}">Hang moi</a></li>
                </ul>

                <!-- Actions -->
                <div class="fs-actions">
                    <button class="fs-search-toggle" onclick="toggleSearch()" title="Tim kiem">
                        <i class="bi bi-search"></i>
                    </button>

                    @guest
                    <a class="fs-icon-btn" href="{{ route('login') }}" title="Dang nhap">
                        <i class="bi bi-person"></i>
                    </a>
                    @else
                    <a class="fs-icon-btn" href="{{ route('cart.index') }}" title="Gio hang">
                        <i class="bi bi-bag"></i>
                        <span class="fs-badge"></span>
                    </a>
                    <a class="fs-icon-btn" href="{{ route('orders.index') }}" title="Don hang">
                        <i class="bi bi-receipt"></i>
                    </a>
                    <div class="dropdown fs-user-dropdown">
                        <a class="fs-icon-btn dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li class="px-3 py-2 mb-1">
                                <div style="font-size:0.75rem;color:var(--fs-muted);text-transform:uppercase;letter-spacing:1px;">Tai khoan</div>
                                <div style="font-weight:600;">{{ Auth::user()->full_name ?? Auth::user()->username }}</div>
                            </li>
                            <li><hr class="dropdown-divider" style="margin:0.25rem 0.5rem;"></li>
                            @if(Auth::user()->role === 'admin')
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Quan tri</a></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person-gear"></i> Thong tin ca nhan</a></li>
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="bi bi-box-seam"></i> Don hang cua toi</a></li>
                            <li><hr class="dropdown-divider" style="margin:0.25rem 0.5rem;"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item" style="color:var(--fs-danger);">
                                        <i class="bi bi-box-arrow-right"></i> Dang xuat
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endguest
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="fs-search-bar" id="searchBar">
            <div class="container">
                <form action="{{ route('books.index') }}" method="GET">
                    <input type="text" class="form-control form-control-lg" name="search"
                        placeholder="Tim kiem san pham, thuong hieu..." value="{{ request('search') }}"
                        style="border:none;border-bottom:2px solid var(--fs-border);border-radius:0;font-family:'Playfair Display',serif;">
                </form>
            </div>
        </div>
    </nav>

    <!-- Toast Alerts -->
    @if(session('success'))
    <div class="fs-toast" onclick="this.remove()">
        <div class="alert alert-success d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="fs-toast" onclick="this.remove()">
        <div class="alert alert-danger d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ session('error') }}
        </div>
    </div>
    @endif

    <main class="fs-animate">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="fs-footer">
        <div class="fs-footer-top">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="fs-footer-brand">
                            FASHION
                            <small>Premium Quality Clothing</small>
                        </div>
                        <p style="font-size:0.875rem;line-height:1.8;max-width:300px;">
                            Mang den nhung san pham thoi trang chat luong cao voi phong cach hien dai va tinh te.
                        </p>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <h6>Danh muc</h6>
                        <ul>
                            <li><a href="{{ route('books.index', ['gender' => 'nam']) }}">Thoi trang Nam</a></li>
                            <li><a href="{{ route('books.index', ['gender' => 'nu']) }}">Thoi trang Nu</a></li>
                            <li><a href="{{ route('books.index', ['sort' => 'latest']) }}">Hang moi ve</a></li>
                            <li><a href="{{ route('books.index') }}">Tat ca san pham</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <h6>Ho tro</h6>
                        <ul>
                            <li><a href="#">Chinh sach doi tra</a></li>
                            <li><a href="#">Van chuyen</a></li>
                            <li><a href="#">Huong dan mua hang</a></li>
                            <li><a href="#">Lien he</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <h6>Lien he</h6>
                        <ul class="fs-footer-contact">
                            <li><i class="bi bi-geo-alt-fill"></i> TP. Ho Chi Minh, Viet Nam</li>
                            <li><i class="bi bi-telephone-fill"></i> 0123 456 789</li>
                            <li><i class="bi bi-envelope-fill"></i> info@fashionstore.vn</li>
                            <li><i class="bi bi-clock-fill"></i> T2 - CN: 8:00 - 22:00</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="fs-footer-bottom">
                <span>&copy; {{ date('Y') }} Fashion Store. All rights reserved.</span>
                <div class="fs-social">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-tiktok"></i></a>
                    <a href="#"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sticky nav shadow
        window.addEventListener('scroll', () => {
            document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 20);
        });

        // Search toggle
        function toggleSearch() {
            const bar = document.getElementById('searchBar');
            bar.classList.toggle('open');
            if (bar.classList.contains('open')) {
                bar.querySelector('input').focus();
            }
        }

        // Auto-dismiss toasts
        document.querySelectorAll('.fs-toast').forEach(t => {
            setTimeout(() => { t.style.opacity = '0'; t.style.transform = 'translateX(60px)'; setTimeout(() => t.remove(), 400); }, 4000);
        });
    </script>
    @stack('scripts')
</body>

</html>
