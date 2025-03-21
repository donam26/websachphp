<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Quản trị Book Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>

  table {
      width: 100%; /* Bảng chiếm toàn bộ chiều rộng */
      border-collapse: collapse; /* Loại bỏ khoảng cách giữa các ô */
    }

    th, td {
      border: 1px solid #ddd; /* Viền cho các ô */
      padding: 8px; /* Khoảng cách bên trong ô */
      text-align: left; /* Căn lề trái */
    }

    /* Responsive: Chuyển bảng thành dạng dọc khi màn hình nhỏ hơn 600px */
    @media (max-width: 600px) {
      table, thead, tbody, th, td, tr {
        display: block; /* Chuyển đổi thành khối */
      }

      thead tr {
        display: none; /* Ẩn phần tiêu đề của bảng */
      }

      tr {
        margin-bottom: 15px; /* Khoảng cách giữa các hàng */
      }

      td {
        position: relative; /* Để hiển thị tiêu đề cột */
        padding-left: 50%; /* Chừa chỗ cho tiêu đề cột */
      }

      td::before {
        content: attr(data-label); /* Lấy tiêu đề từ thuộc tính data-label */
        position: absolute;
        left: 10px; /* Căn lề trái */
        top: 50%;
        transform: translateY(-50%); /* Căn giữa theo chiều dọc */
        font-weight: bold; /* In đậm tiêu đề */
      }
    }

        :root {
            --primary-color: #191919;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
        }

        body {
            background-color: #f8f9fc;
        }

        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 10%, #224abe 100%);
            min-height: 100vh;
            transition: all 0.3s;
        }

        .sidebar .nav-item {
            position: relative;
            margin-bottom: 5px;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }

        .sidebar .nav-link i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }

        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 5px;
        }

        .topbar {
            background-color: #fff;
            box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15);
        }

        .topbar .dropdown-toggle::after {
            display: none;
        }

        .card {
            border: none;
            border-radius: .35rem;
            box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15);
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }

        .dropdown-menu {
            box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15);
            border: none;
        }

        .alert {
            border: none;
            border-radius: .35rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-4">
                    <div class="text-center mb-4">
                        <div class="text-white"><img style ='width: 120px;  height: 120px; '
                         src="https://hqland.id.vn/storage/products/images/logo%20HQ.png" alt="Logo" class="logo"></div>

                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Nổi bật
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                                <i class="bi bi-house"></i>
                                <span>Sản phẩm thuê </span>
                            </a>
                        </li>

                        {{-- <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people"></i> Quản lý người dùng
                            </a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.customer.*') ? 'active' : '' }}"
                                href="{{ route('admin.customer.index') }}">
                                <i class="bi bi-person-badge"></i>
                                <span>Khách hàng</span>
                            </a>
                        </li>
                        @if(Auth::user()->IsDelete === 1)
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.Adduser.*') ? 'active' : '' }}"
                                href="{{ route('admin.Adduser.index') }}">
                                <i class="bi bi-person-badge"></i>
                                <span>Tạo nhân viên</span>
                            </a>
                        </li>
                        @endif   
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <nav class="navbar navbar-expand-lg navbar-light topbar mb-4 static-top">
                    <div class="container-fluid">
                        <button class="btn btn-link d-md-none" type="button" data-bs-toggle="collapse" data-bs-target=".sidebar">
                            <i class="bi bi-list"></i>
                        </button>

                        <div class="d-flex align-items-center ms-auto">
                            <div class="dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle me-2"></i>
                                    <span>{{ Auth::user()->username }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile') }}">
                                            <i class="bi bi-person me-2"></i> Thông tin cá nhân
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
                            </div>
                        </div>
                    </div>
                </nav>

                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @stack('scripts')
</body>
</html>
