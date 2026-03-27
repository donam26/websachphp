<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Quan tri Fashion Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ad-primary: #1a1a2e;
            --ad-secondary: #16213e;
            --ad-accent: #c9a96e;
            --ad-accent-light: #e8d5b5;
            --ad-surface: #f5f3f0;
            --ad-card: #ffffff;
            --ad-text: #2d2d2d;
            --ad-muted: #8c8c8c;
            --ad-border: #e8e6e3;
            --ad-success: #27ae60;
            --ad-danger: #e74c3c;
            --ad-warning: #f39c12;
            --ad-info: #3498db;
            --ad-radius: 16px;
            --ad-radius-sm: 10px;
            --ad-shadow: 0 4px 24px rgba(0,0,0,0.06);
            --ad-transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            color: var(--ad-text);
            background: var(--ad-surface);
            -webkit-font-smoothing: antialiased;
        }

        /* ── Sidebar ── */
        .ad-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 260px;
            background: var(--ad-primary);
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: var(--ad-transition);
            overflow-y: auto;
        }

        .ad-sidebar-brand {
            padding: 1.75rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .ad-sidebar-brand a {
            font-family: 'Playfair Display', serif;
            font-size: 1.35rem;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            display: block;
        }

        .ad-sidebar-brand small {
            display: block;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.6rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--ad-accent);
            margin-top: 2px;
        }

        .ad-sidebar-menu {
            padding: 1rem 0.75rem;
            flex: 1;
        }

        .ad-sidebar-label {
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.3);
            padding: 1rem 0.75rem 0.5rem;
        }

        .ad-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.7rem 0.75rem;
            margin-bottom: 2px;
            border-radius: 10px;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: var(--ad-transition);
        }

        .ad-nav-item i {
            font-size: 1.1rem;
            width: 22px;
            text-align: center;
        }

        .ad-nav-item:hover {
            color: #fff;
            background: rgba(255,255,255,0.08);
        }

        .ad-nav-item.active {
            color: #fff;
            background: rgba(201, 169, 110, 0.2);
        }

        .ad-nav-item.active i {
            color: var(--ad-accent);
        }

        /* ── Main Content ── */
        .ad-main {
            margin-left: 260px;
            min-height: 100vh;
            transition: var(--ad-transition);
        }

        /* ── Top Bar ── */
        .ad-topbar {
            position: sticky;
            top: 0;
            z-index: 1030;
            background: rgba(245, 243, 240, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--ad-border);
            padding: 0 2rem;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .ad-topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .ad-topbar-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem;
            font-weight: 600;
            color: var(--ad-primary);
        }

        .ad-topbar-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .ad-topbar-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: none;
            background: var(--ad-card);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ad-text);
            font-size: 1rem;
            cursor: pointer;
            transition: var(--ad-transition);
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .ad-topbar-btn:hover { background: var(--ad-primary); color: #fff; }

        .ad-user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.35rem 1rem 0.35rem 0.5rem;
            background: var(--ad-card);
            border-radius: 100px;
            border: none;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--ad-text);
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            text-decoration: none;
            transition: var(--ad-transition);
        }

        .ad-user-pill:hover { box-shadow: var(--ad-shadow); }

        .ad-user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--ad-accent);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
        }

        .ad-user-pill .dropdown-toggle::after { display: none; }

        .ad-user-pill .dropdown-menu {
            border: none;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            border-radius: var(--ad-radius-sm);
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        .ad-user-pill .dropdown-item {
            border-radius: 8px;
            padding: 0.55rem 0.75rem;
            font-size: 0.85rem;
            transition: var(--ad-transition);
        }

        .ad-user-pill .dropdown-item:hover { background: var(--ad-surface); }

        /* ── Content ── */
        .ad-content {
            padding: 1.5rem 2rem 3rem;
        }

        /* ── Cards ── */
        .ad-card {
            background: var(--ad-card);
            border: 1px solid var(--ad-border);
            border-radius: var(--ad-radius);
            box-shadow: var(--ad-shadow);
            overflow: hidden;
        }

        .ad-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--ad-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .ad-card-header h5 {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
            color: var(--ad-primary);
        }

        .ad-card-body { padding: 1.5rem; }

        /* ── Stat Cards ── */
        .ad-stat {
            background: var(--ad-card);
            border: 1px solid var(--ad-border);
            border-radius: var(--ad-radius);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: var(--ad-transition);
            box-shadow: var(--ad-shadow);
        }

        .ad-stat:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.1);
        }

        .ad-stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .ad-stat-icon.green { background: #e8f5e9; color: #2e7d32; }
        .ad-stat-icon.blue { background: #e3f2fd; color: #1565c0; }
        .ad-stat-icon.purple { background: #f3e5f5; color: #7b1fa2; }
        .ad-stat-icon.amber { background: #fff8e1; color: #f57f17; }

        .ad-stat-label {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--ad-muted);
            margin-bottom: 4px;
        }

        .ad-stat-value {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--ad-primary);
            line-height: 1;
        }

        /* ── Tables ── */
        .ad-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .ad-table thead th {
            background: var(--ad-surface);
            padding: 0.75rem 1rem;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--ad-muted);
            border-bottom: 1px solid var(--ad-border);
            white-space: nowrap;
        }

        .ad-table tbody td {
            padding: 0.85rem 1rem;
            font-size: 0.875rem;
            border-bottom: 1px solid var(--ad-border);
            vertical-align: middle;
        }

        .ad-table tbody tr:last-child td { border-bottom: none; }

        .ad-table tbody tr {
            transition: var(--ad-transition);
        }

        .ad-table tbody tr:hover {
            background: rgba(201, 169, 110, 0.04);
        }

        /* ── Badges ── */
        .ad-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.3rem 0.65rem;
            border-radius: 100px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .ad-badge-success { background: #e8f5e9; color: #2e7d32; }
        .ad-badge-danger { background: #fce4ec; color: #c62828; }
        .ad-badge-warning { background: #fff8e1; color: #f57f17; }
        .ad-badge-info { background: #e3f2fd; color: #1565c0; }
        .ad-badge-primary { background: #ede7f6; color: #4527a0; }
        .ad-badge-dark { background: var(--ad-primary); color: #fff; }

        /* ── Buttons ── */
        .ad-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.55rem 1.25rem;
            border-radius: 10px;
            font-size: 0.82rem;
            font-weight: 600;
            border: 1.5px solid transparent;
            transition: var(--ad-transition);
            cursor: pointer;
            text-decoration: none;
        }

        .ad-btn-primary { background: var(--ad-primary); color: #fff; border-color: var(--ad-primary); }
        .ad-btn-primary:hover { background: transparent; color: var(--ad-primary); }

        .ad-btn-accent { background: var(--ad-accent); color: #fff; border-color: var(--ad-accent); }
        .ad-btn-accent:hover { background: transparent; color: var(--ad-accent); }

        .ad-btn-outline { background: transparent; color: var(--ad-text); border-color: var(--ad-border); }
        .ad-btn-outline:hover { border-color: var(--ad-primary); color: var(--ad-primary); }

        .ad-btn-danger { background: #fce4ec; color: #c62828; border-color: #fce4ec; }
        .ad-btn-danger:hover { background: #c62828; color: #fff; border-color: #c62828; }

        .ad-btn-sm { padding: 0.4rem 0.75rem; font-size: 0.78rem; border-radius: 8px; }

        .ad-btn-icon {
            width: 36px;
            height: 36px;
            padding: 0;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* ── Alerts ── */
        .ad-alert {
            border: none;
            border-radius: var(--ad-radius-sm);
            padding: 1rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .ad-alert-success { background: linear-gradient(135deg, #e8f5e9, #c8e6c9); color: #1b5e20; }
        .ad-alert-danger { background: linear-gradient(135deg, #fce4ec, #f8bbd0); color: #b71c1c; }

        /* Forms */
        .ad-content .form-control,
        .ad-content .form-select {
            border: 1.5px solid var(--ad-border);
            border-radius: var(--ad-radius-sm);
            padding: 0.65rem 1rem;
            font-size: 0.875rem;
            transition: var(--ad-transition);
        }

        .ad-content .form-control:focus,
        .ad-content .form-select:focus {
            border-color: var(--ad-accent);
            box-shadow: 0 0 0 3px rgba(201, 169, 110, 0.15);
        }

        .ad-content .form-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--ad-text);
            margin-bottom: 0.35rem;
        }

        /* Pagination */
        .ad-content .pagination { gap: 4px; }
        .ad-content .page-link {
            border-radius: 8px !important;
            border: 1px solid var(--ad-border);
            color: var(--ad-text);
            font-size: 0.85rem;
            transition: var(--ad-transition);
        }
        .ad-content .page-link:hover { background: var(--ad-primary); border-color: var(--ad-primary); color: #fff; }
        .ad-content .page-item.active .page-link { background: var(--ad-primary); border-color: var(--ad-primary); }

        /* Modal */
        .modal-content {
            border: none;
            border-radius: var(--ad-radius);
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }

        .modal-header {
            border-bottom: 1px solid var(--ad-border);
            padding: 1.25rem 1.5rem;
        }

        .modal-title {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .ad-sidebar { transform: translateX(-100%); }
            .ad-sidebar.show { transform: translateX(0); }
            .ad-main { margin-left: 0; }
        }

        /* Scrollbar */
        .ad-sidebar::-webkit-scrollbar { width: 4px; }
        .ad-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--ad-border); border-radius: 10px; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .ad-animate { animation: fadeUp 0.5s ease forwards; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="ad-sidebar" id="sidebar">
        <div class="ad-sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">
                FASHION
                <small>Admin Panel</small>
            </a>
        </div>
        <div class="ad-sidebar-menu">
            <div class="ad-sidebar-label">Tong quan</div>
            <a class="ad-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>

            <div class="ad-sidebar-label" style="margin-top:0.75rem;">Quan ly</div>
            <a class="ad-nav-item {{ request()->routeIs('admin.books.*') ? 'active' : '' }}" href="{{ route('admin.books.index') }}">
                <i class="bi bi-handbag"></i> San pham
            </a>
            <a class="ad-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                <i class="bi bi-bookmark"></i> Danh muc
            </a>
            <a class="ad-nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                <i class="bi bi-box-seam"></i> Don hang
            </a>
            <a class="ad-nav-item {{ request()->routeIs('admin.discounts.*') ? 'active' : '' }}" href="{{ route('admin.discounts.index') }}">
                <i class="bi bi-tag"></i> Khuyen mai
            </a>

            <div class="ad-sidebar-label" style="margin-top:0.75rem;">He thong</div>
            <a class="ad-nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                <i class="bi bi-building"></i> San pham thue
            </a>
            <a class="ad-nav-item {{ request()->routeIs('admin.customer.*') ? 'active' : '' }}" href="{{ route('admin.customer.index') }}">
                <i class="bi bi-people"></i> Khach hang
            </a>
            @if(Auth::user()->IsDelete === 1)
            <a class="ad-nav-item {{ request()->routeIs('admin.Adduser.*') ? 'active' : '' }}" href="{{ route('admin.Adduser.index') }}">
                <i class="bi bi-person-plus"></i> Tao nhan vien
            </a>
            @endif
        </div>
    </aside>

    <!-- Main -->
    <div class="ad-main">
        <!-- Top Bar -->
        <header class="ad-topbar">
            <div class="ad-topbar-left">
                <button class="ad-topbar-btn d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="bi bi-list"></i>
                </button>
                <span class="ad-topbar-title">@yield('title', 'Dashboard')</span>
            </div>
            <div class="ad-topbar-right">
                <a class="ad-topbar-btn" href="{{ route('home') }}" title="Xem trang chu">
                    <i class="bi bi-house"></i>
                </a>
                <div class="dropdown">
                    <button class="ad-user-pill dropdown-toggle" data-bs-toggle="dropdown">
                        <div class="ad-user-avatar">{{ strtoupper(substr(Auth::user()->username, 0, 1)) }}</div>
                        <span>{{ Auth::user()->username }}</span>
                        <i class="bi bi-chevron-down" style="font-size:0.7rem;color:var(--ad-muted);"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i> Thong tin ca nhan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Dang xuat</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="ad-content ad-animate">
            @if(session('success'))
                <div class="ad-alert ad-alert-success">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="ad-alert ad-alert-danger">
                    <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>
</html>
