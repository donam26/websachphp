@extends('layouts.app')

@section('title', 'Đăng ký')

@section('content')
<div class="auth-wrapper">
    <div class="row g-0 auth-card">
        <div class="col-md-5 auth-banner d-none d-md-flex">
            <div class="auth-banner-content">
                <i class="bi bi-person-plus display-1 mb-3"></i>
                <h2>Tạo tài khoản mới</h2>
                <p>Tham gia ngay để nhận ưu đãi từ BookStore.</p>
                <div class="welcome-gift mt-4">
                    <i class="bi bi-gift-fill"></i>
                    <div>
                        <strong>Quà chào mừng</strong>
                        <small>Giảm 50K cho đơn đầu tiên</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7 auth-form-side">
            <div class="auth-form-content">
                <h3 class="mb-1">Đăng ký tài khoản</h3>
                <p class="text-muted small mb-4">Đã có tài khoản? <a href="{{ route('login') }}" class="text-primary">Đăng nhập ngay</a></p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Tên đăng nhập</label>
                            <div class="input-with-icon">
                                <i class="bi bi-person"></i>
                                <input type="text" class="form-control @error('username') is-invalid @enderror"
                                       name="username" value="{{ old('username') }}" placeholder="vd: nguyenvana" required>
                            </div>
                            @error('username')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Email</label>
                            <div class="input-with-icon">
                                <i class="bi bi-envelope"></i>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}" placeholder="email@example.com" required>
                            </div>
                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Họ và tên</label>
                            <div class="input-with-icon">
                                <i class="bi bi-person-badge"></i>
                                <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                       name="full_name" value="{{ old('full_name') }}" placeholder="Nguyễn Văn A" required>
                            </div>
                            @error('full_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Số điện thoại</label>
                            <div class="input-with-icon">
                                <i class="bi bi-telephone"></i>
                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                                       name="phone_number" value="{{ old('phone_number') }}" placeholder="0912 345 678" required>
                            </div>
                            @error('phone_number')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Địa chỉ</label>
                            <div class="input-with-icon">
                                <i class="bi bi-geo-alt"></i>
                                <input type="text" class="form-control @error('address') is-invalid @enderror"
                                       name="address" value="{{ old('address') }}" placeholder="Số nhà, phường/xã, quận/huyện, tỉnh/TP" required>
                            </div>
                            @error('address')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Mật khẩu</label>
                            <div class="input-with-icon">
                                <i class="bi bi-lock"></i>
                                <input type="password" id="pwd" class="form-control @error('password') is-invalid @enderror"
                                       name="password" placeholder="Tối thiểu 8 ký tự" required>
                                <button type="button" class="icon-toggle" onclick="togglePwd('pwd', this)"><i class="bi bi-eye"></i></button>
                            </div>
                            @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Xác nhận mật khẩu</label>
                            <div class="input-with-icon">
                                <i class="bi bi-shield-lock"></i>
                                <input type="password" id="pwd2" class="form-control" name="password_confirmation" placeholder="Nhập lại mật khẩu" required>
                                <button type="button" class="icon-toggle" onclick="togglePwd('pwd2', this)"><i class="bi bi-eye"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="form-check mt-3">
                        <input type="checkbox" class="form-check-input" id="agree" required>
                        <label class="form-check-label small" for="agree">
                            Tôi đồng ý với <a href="#" class="text-primary">Điều khoản dịch vụ</a> và <a href="#" class="text-primary">Chính sách bảo mật</a>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 mt-3 fw-bold">
                        <i class="bi bi-person-plus me-2"></i>Đăng ký tài khoản
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.auth-wrapper { min-height: 70vh; display: flex; align-items: center; justify-content: center; padding: 20px 0; }
.auth-card { background: #fff; border-radius: 16px; box-shadow: var(--shadow-lg); overflow: hidden; max-width: 1000px; width: 100%; }
.auth-banner {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: #fff;
    padding: 48px 36px;
    align-items: center;
    position: relative;
    overflow: hidden;
}
.auth-banner::after {
    content: '';
    position: absolute;
    right: -80px; bottom: -80px;
    width: 240px; height: 240px;
    background: rgba(255,255,255,.08);
    border-radius: 50%;
}
.auth-banner-content { position: relative; z-index: 2; }
.auth-banner-content h2 { font-weight: 800; margin-bottom: 14px; }
.welcome-gift {
    background: rgba(255,255,255,.15);
    border-radius: 12px;
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 14px;
    backdrop-filter: blur(8px);
}
.welcome-gift i { font-size: 32px; color: #fdd835; }
.welcome-gift strong { display: block; font-size: 15px; }
.welcome-gift small { opacity: .85; }

.auth-form-side { padding: 36px 36px; max-height: 90vh; overflow-y: auto; }
.input-with-icon { position: relative; }
.input-with-icon > i:first-child {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
}
.input-with-icon input { padding-left: 40px; }
.icon-toggle {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--text-muted);
    padding: 6px 10px;
}

@media (max-width: 768px) {
    .auth-form-side { padding: 28px 20px; }
}
</style>
@endpush

@push('scripts')
<script>
function togglePwd(id, btn) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.querySelector('i').className = input.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
</script>
@endpush
@endsection
