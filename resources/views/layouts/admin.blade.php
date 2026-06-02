<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - BookStore Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #818cf8;
            --sidebar-bg: #1f2937;
            --sidebar-dark: #111827;
            --sidebar-text: rgba(255, 255, 255, .75);
            --sidebar-active: var(--primary);
            --topbar-bg: #ffffff;
            --content-bg: #f3f4f6;
            --text-dark: #111827;
            --text-muted: #6b7280;
            --border: #e5e7eb;
            --success: #1ca672;
            --info: #2196f3;
            --warning: #ff9800;
            --danger: #e53935;
        }

        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, sans-serif;
            background: var(--content-bg);
            font-size: 14px;
            color: var(--text-dark);
            margin: 0;
        }

        a { text-decoration: none; }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ===== Sidebar ===== */
        .sidebar {
            width: 260px;
            flex-shrink: 0;
            background: var(--sidebar-bg);
            color: #fff;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1030;
            transition: transform .3s ease;
        }
        .sidebar-brand {
            padding: 18px 20px;
            background: var(--sidebar-dark);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
            font-weight: 700;
            color: #fff;
        }
        .sidebar-brand .brand-icon {
            width: 38px; height: 38px;
            background: var(--primary);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }
        .sidebar-brand small {
            display: block;
            font-size: 10.5px;
            font-weight: 500;
            opacity: .6;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-top: 2px;
        }
        .sidebar-section {
            padding: 16px 20px 6px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: rgba(255,255,255,.4);
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            flex: 1;
            overflow-y: auto;
        }
        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 20px;
            color: var(--sidebar-text);
            font-size: 14px;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all .2s ease;
        }
        .sidebar-menu li a i {
            font-size: 17px;
            width: 22px;
            text-align: center;
        }
        .sidebar-menu li a:hover {
            background: rgba(255,255,255,.06);
            color: #fff;
        }
        .sidebar-menu li a.active {
            background: rgba(79,70,229,.18);
            color: #fff;
            border-left-color: var(--primary);
        }
        .sidebar-footer {
            padding: 14px 20px;
            border-top: 1px solid rgba(255,255,255,.08);
            font-size: 12px;
            color: rgba(255,255,255,.5);
        }

        /* ===== Main area ===== */
        .main-area {
            flex: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            background: var(--topbar-bg);
            padding: 12px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 1px 3px rgba(0,0,0,.04);
        }
        .topbar .toggle-btn {
            background: none;
            border: none;
            font-size: 22px;
            color: var(--text-dark);
            padding: 4px 10px;
        }
        .topbar .page-title {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
        }
        .topbar .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .topbar .topbar-link {
            padding: 6px 12px;
            border-radius: 8px;
            color: var(--text-muted);
            transition: .2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .topbar .topbar-link:hover { background: var(--content-bg); color: var(--text-dark); }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px;
            border-radius: 999px;
            background: var(--content-bg);
            color: var(--text-dark);
            font-weight: 500;
        }
        .user-avatar {
            width: 32px; height: 32px;
            background: var(--primary);
            color: #fff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
        }

        .content-area {
            padding: 24px;
            flex: 1;
        }

        /* ===== Cards / Components ===== */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,.05);
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 14px 20px;
            font-weight: 600;
        }
        .card-body { padding: 20px; }

        .stat-card {
            border-radius: 12px;
            padding: 22px;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,.08);
        }
        .stat-card .stat-icon {
            position: absolute;
            right: 12px;
            top: 12px;
            font-size: 60px;
            opacity: .18;
        }
        .stat-card .stat-label {
            font-size: 12.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            opacity: .85;
            margin-bottom: 8px;
        }
        .stat-card .stat-value {
            font-size: 26px;
            font-weight: 700;
            line-height: 1.2;
        }
        .stat-card .stat-foot { opacity: .8; font-size: 12px; margin-top: 6px; }
        .stat-card.primary { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); }
        .stat-card.success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .stat-card.info { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
        .stat-card.warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }

        .table {
            margin-bottom: 0;
        }
        .table th {
            background: #f9fafb;
            font-weight: 600;
            font-size: 12.5px;
            text-transform: uppercase;
            letter-spacing: .3px;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
            padding: 12px 16px;
        }
        .table td {
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
        }
        .table-hover tbody tr:hover { background: #fafbfc; }

        .badge {
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 11.5px;
        }
        .badge-soft-success { background: rgba(16,185,129,.12); color: #047857; }
        .badge-soft-danger { background: rgba(239,68,68,.12); color: #b91c1c; }
        .badge-soft-warning { background: rgba(245,158,11,.12); color: #b45309; }
        .badge-soft-info { background: rgba(59,130,246,.12); color: #1d4ed8; }
        .badge-soft-primary { background: rgba(79,70,229,.12); color: var(--primary); }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 .2rem rgba(79,70,229,.12);
        }

        .alert {
            border: none;
            border-radius: 8px;
            padding: 12px 16px;
            font-weight: 500;
        }
        .alert-success { background: rgba(16,185,129,.12); color: #047857; }
        .alert-danger { background: rgba(239,68,68,.12); color: #b91c1c; }

        /* ===== Responsive ===== */
        .sidebar-overlay { display: none; }
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-area { margin-left: 0; }
            .sidebar-overlay {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,.4);
                z-index: 1029;
                opacity: 0;
                pointer-events: none;
                transition: opacity .3s;
            }
            .sidebar-overlay.show { opacity: 1; pointer-events: auto; }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="admin-wrapper">
        {{-- Sidebar --}}
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <span class="brand-icon"><i class="bi bi-book-half"></i></span>
                <div>
                    BookStore
                    <small>Admin Panel</small>
                </div>
            </div>

            <div class="sidebar-section">Tổng quan</div>
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
            </ul>

            <div class="sidebar-section">Quản lý sách</div>
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.books.index') }}" class="{{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
                        <i class="bi bi-book"></i> Sách
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i> Danh mục
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.authors.index') }}" class="{{ request()->routeIs('admin.authors.*') ? 'active' : '' }}">
                        <i class="bi bi-person-lines-fill"></i> Tác giả
                    </a>
                </li>
            </ul>

            <div class="sidebar-section">Bán hàng</div>
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <i class="bi bi-receipt"></i> Đơn hàng
                    </a>
                </li>
            </ul>

            <div class="sidebar-section">Hệ thống</div>
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Người dùng
                    </a>
                </li>
                <li>
                    <a href="{{ route('home') }}">
                        <i class="bi bi-house"></i> Về trang chủ
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                v1.0 &mdash; &copy; {{ date('Y') }} BookStore
            </div>
        </aside>

        <div class="sidebar-overlay" id="sidebar-overlay"></div>

        {{-- Main area --}}
        <div class="main-area">
            <header class="topbar">
                <div class="d-flex align-items-center gap-2">
                    <button class="toggle-btn d-lg-none" id="toggle-sidebar"><i class="bi bi-list"></i></button>
                    <h1 class="page-title">@yield('page-title', View::yieldContent('title') ?: 'Dashboard')</h1>
                </div>
                <div class="topbar-right">
                    <a href="{{ route('home') }}" target="_blank" class="topbar-link d-none d-md-inline-flex">
                        <i class="bi bi-box-arrow-up-right"></i> Xem website
                    </a>
                    <div class="dropdown">
                        <a class="user-pill dropdown-toggle text-decoration-none" data-bs-toggle="dropdown" role="button">
                            <span class="user-avatar">{{ strtoupper(substr(Auth::user()->full_name ?? Auth::user()->username, 0, 1)) }}</span>
                            <span class="d-none d-md-inline">{{ Auth::user()->full_name ?? Auth::user()->username }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>Thông tin cá nhân</a></li>
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="bi bi-receipt me-2"></i>Đơn hàng</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">@csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <div class="content-area">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                        <button class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.getElementById('toggle-sidebar')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebar-overlay').classList.toggle('show');
        });
        document.getElementById('sidebar-overlay')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('show');
            this.classList.remove('show');
        });
    </script>
    @stack('scripts')
</body>

</html>
