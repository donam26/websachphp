@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div class="auth-wrapper">
    <div class="row g-0 auth-card">
        <div class="col-md-6 auth-banner d-none d-md-flex">
            <div class="auth-banner-content">
                <i class="bi bi-book-half display-1 mb-3"></i>
                <h2>Chào mừng trở lại!</h2>
                <p>Đăng nhập để tiếp tục hành trình khám phá hàng ngàn cuốn sách hay mỗi ngày.</p>
                <ul class="benefit-list">
                    <li><i class="bi bi-check2-circle me-2"></i>Theo dõi đơn hàng dễ dàng</li>
                    <li><i class="bi bi-check2-circle me-2"></i>Lưu sách yêu thích</li>
                    <li><i class="bi bi-check2-circle me-2"></i>Nhận thông báo sách mới</li>
                    <li><i class="bi bi-check2-circle me-2"></i>Mã giảm giá độc quyền</li>
                </ul>
            </div>
        </div>
        <div class="col-md-6 auth-form-side">
            <div class="auth-form-content">
                <h3 class="mb-1">Đăng nhập</h3>
                <p class="text-muted small mb-4">Nhập email và mật khẩu của bạn</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email</label>
                        <div class="input-with-icon">
                            <i class="bi bi-envelope"></i>
                            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" placeholder="email@example.com" required autofocus>
                        </div>
                        @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label class="form-label small fw-semibold">Mật khẩu</label>
                            <a href="#" class="small text-primary">Quên mật khẩu?</a>
                        </div>
                        <div class="input-with-icon">
                            <i class="bi bi-lock"></i>
                            <input type="password" id="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                                   name="password" placeholder="••••••••" required>
                            <button type="button" class="icon-toggle" onclick="togglePwd('password', this)"><i class="bi bi-eye"></i></button>
                        </div>
                        @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small" for="remember">Ghi nhớ đăng nhập</label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập
                    </button>
                </form>

                <div class="text-center my-3 text-muted small">hoặc</div>

                <p class="text-center small mb-0">
                    Chưa có tài khoản?
                    <a href="{{ route('register') }}" class="text-primary fw-semibold">Đăng ký ngay</a>
                </p>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.auth-wrapper {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px 0;
}
.auth-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    max-width: 900px;
    width: 100%;
}
.auth-banner {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: #fff;
    padding: 48px 40px;
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
.auth-banner-content .benefit-list {
    list-style: none;
    padding: 0;
    margin: 20px 0 0;
}
.auth-banner-content .benefit-list li { padding: 6px 0; opacity: .95; }

.auth-form-side { padding: 48px 40px; }
.auth-form-content h3 { font-weight: 700; }

.input-with-icon { position: relative; }
.input-with-icon > i:first-child {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 18px;
}
.input-with-icon input { padding-left: 42px; }
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
