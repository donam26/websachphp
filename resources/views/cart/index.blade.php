@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
        <li class="breadcrumb-item active">Giỏ hàng</li>
    </ol>
</nav>

@if($cartItems->isEmpty())
    <div class="card">
        <div class="card-body empty-state">
            <i class="bi bi-cart-x"></i>
            <h5>Giỏ hàng của bạn đang trống</h5>
            <p>Hãy chọn sách yêu thích và thêm vào giỏ hàng nhé!</p>
            <a href="{{ route('books.index') }}" class="btn btn-primary mt-2">
                <i class="bi bi-arrow-left me-1"></i> Tiếp tục mua sắm
            </a>
        </div>
    </div>
@else
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">
                        <i class="bi bi-cart3 me-2 text-primary"></i>Giỏ hàng
                        <span class="text-muted fs-6">({{ $cartItems->count() }} sản phẩm)</span>
                    </h4>
                    <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Xoá toàn bộ giỏ hàng?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger text-decoration-none p-0">
                            <i class="bi bi-trash me-1"></i>Xoá tất cả
                        </button>
                    </form>
                </div>

                <div class="cart-items">
                    @foreach($cartItems as $item)
                        <div class="cart-row">
                            <div class="cart-thumb">
                                <a href="{{ route('books.show', $item->book) }}">
                                    <img src="{{ $item->book?->image_url }}"
                                         alt="{{ $item->book?->title }}"
                                         onerror="this.src='https://placehold.co/120x160/f4f6f8/c92127?text=Book'">
                                </a>
                            </div>
                            <div class="cart-info">
                                <a href="{{ route('books.show', $item->book) }}" class="cart-title">{{ $item->book?->title }}</a>
                                <div class="cart-meta">
                                    <span><i class="bi bi-person me-1"></i>{{ $item->book?->author }}</span>
                                    @if($item->book?->category)
                                        <span class="badge badge-soft-primary ms-2">{{ $item->book->category->name }}</span>
                                    @endif
                                </div>
                                <div class="text-muted small mt-1">
                                    Đơn giá: <strong class="text-dark">{{ number_format($item->book?->price ?? 0, 0, ',', '.') }}đ</strong>
                                    @if($item->book && $item->book->quantity < 5)
                                        <span class="text-warning small ms-2"><i class="bi bi-exclamation-triangle"></i> Chỉ còn {{ $item->book->quantity }} cuốn</span>
                                    @endif
                                </div>
                            </div>
                            <div class="cart-qty">
                                <form action="{{ route('cart.update', $item) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="quantity-control">
                                        <button class="btn-qty" type="submit" name="action" value="dec" {{ $item->quantity <= 1 ? 'disabled' : '' }}><i class="bi bi-dash"></i></button>
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->book?->quantity ?? 99 }}" onchange="this.form.submit()">
                                        <button class="btn-qty" type="submit" name="action" value="inc" {{ $item->book && $item->quantity >= $item->book->quantity ? 'disabled' : '' }}><i class="bi bi-plus"></i></button>
                                    </div>
                                </form>
                            </div>
                            <div class="cart-total">
                                <div class="text-primary fw-bold fs-5">{{ number_format($item->subtotal, 0, ',', '.') }}đ</div>
                                <form action="{{ route('cart.remove', $item) }}" method="POST" class="mt-1">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0 small text-decoration-none">
                                        <i class="bi bi-trash me-1"></i>Xoá
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <a href="{{ route('books.index') }}" class="btn btn-outline-primary mt-3">
                    <i class="bi bi-arrow-left me-1"></i>Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card sticky-summary">
            <div class="card-body">
                <h5 class="mb-3">Thông tin đơn hàng</h5>

                <form id="discount-form" class="mb-3">
                    @csrf
                    <label class="form-label small text-muted">Mã giảm giá</label>
                    <div class="input-group">
                        <input type="text" name="code" id="discount-code-input" class="form-control text-uppercase"
                               placeholder="Nhập mã" value="{{ $discountCode ?? '' }}">
                        @if($discountCode)
                            <button class="btn btn-outline-danger" type="button" id="remove-discount-btn">Bỏ mã</button>
                        @else
                            <button class="btn btn-outline-primary" type="submit">Áp dụng</button>
                        @endif
                    </div>
                    <div id="discount-message" class="small mt-2"></div>
                </form>

                <div class="summary-line">
                    <span>Tạm tính:</span>
                    <span id="subtotal">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                </div>
                <div class="summary-line">
                    <span>Phí vận chuyển:</span>
                    <span id="shipping-fee">{{ $shippingFee > 0 ? number_format($shippingFee, 0, ',', '.') . 'đ' : 'Miễn phí' }}</span>
                </div>
                <div id="discount-amount-row" class="summary-line" style="{{ $discountAmount > 0 ? '' : 'display:none;' }}">
                    <span>Giảm giá:</span>
                    <span class="text-danger" id="discount-amount">-{{ number_format($discountAmount, 0, ',', '.') }}đ</span>
                </div>
                <hr>
                <div class="summary-line total-line">
                    <span>Tổng cộng:</span>
                    <span class="text-primary" id="total-amount">{{ number_format($total, 0, ',', '.') }}đ</span>
                </div>
                @if($subtotal < 250000)
                    <small class="text-muted d-block">Mua thêm {{ number_format(250000 - $subtotal, 0, ',', '.') }}đ để được miễn phí giao hàng</small>
                @endif

                <form action="{{ route('orders.checkout') }}" method="POST" class="mt-3" id="checkout-form">
                    @csrf
                    <h6 class="mt-3 mb-2">Thông tin giao hàng</h6>
                    <div class="mb-2">
                        <input type="text" class="form-control @error('shipping_name') is-invalid @enderror" name="shipping_name"
                               value="{{ old('shipping_name', auth()->user()->full_name) }}" placeholder="Họ tên người nhận" required>
                        @error('shipping_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2">
                        <input type="text" class="form-control @error('shipping_phone') is-invalid @enderror" name="shipping_phone"
                               value="{{ old('shipping_phone', auth()->user()->phone_number) }}" placeholder="Số điện thoại" required>
                        @error('shipping_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2">
                        <textarea class="form-control @error('shipping_address') is-invalid @enderror" name="shipping_address" rows="2"
                                  placeholder="Địa chỉ giao hàng" required>{{ old('shipping_address', auth()->user()->address) }}</textarea>
                        @error('shipping_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" name="note" rows="2" placeholder="Ghi chú (không bắt buộc)">{{ old('note') }}</textarea>
                    </div>

                    <h6 class="mb-2">Phương thức thanh toán</h6>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="cod" checked>
                        <i class="bi bi-cash-coin text-warning"></i>
                        <div>
                            <strong>Thanh toán khi nhận hàng (COD)</strong>
                            <small class="text-muted d-block">Trả tiền mặt khi shipper giao</small>
                        </div>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="vnpay">
                        <i class="bi bi-credit-card-2-front text-info"></i>
                        <div>
                            <strong>Thanh toán qua VNPay</strong>
                            <small class="text-muted d-block">Thẻ ATM/Visa/Master/QR</small>
                        </div>
                    </label>

                    <button type="submit" class="btn btn-primary btn-lg w-100 mt-3 fw-bold">
                        <i class="bi bi-lock-fill me-2"></i>Đặt hàng ngay
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@push('styles')
<style>
    .cart-items > .cart-row + .cart-row { border-top: 1px solid var(--border-light); padding-top: 16px; margin-top: 16px; }
    .cart-row { display: grid; grid-template-columns: 100px 1fr 150px 140px; gap: 16px; align-items: center; }
    .cart-thumb img { width: 100%; aspect-ratio: 3/4; object-fit: cover; border-radius: 8px; }
    .cart-title { font-weight: 600; color: var(--text-dark); font-size: 15px; line-height: 1.4; }
    .cart-title:hover { color: var(--primary); }
    .cart-meta { font-size: 12.5px; color: var(--text-muted); margin-top: 4px; }
    .cart-total { text-align: right; }
    .quantity-control { display: inline-flex; align-items: center; border: 1px solid var(--border-light); border-radius: 6px; overflow: hidden; background: #fff; }
    .btn-qty { width: 32px; height: 32px; background: #fff; border: none; color: var(--text-dark); }
    .btn-qty:hover:not(:disabled) { background: var(--bg-soft); color: var(--primary); }
    .btn-qty:disabled { opacity: .4; cursor: not-allowed; }
    .quantity-control input { width: 48px; height: 32px; border: none; text-align: center; font-weight: 600; outline: none; border-left: 1px solid var(--border-light); border-right: 1px solid var(--border-light); font-size: 14px; }
    .sticky-summary { position: sticky; top: 150px; }
    .summary-line { display: flex; justify-content: space-between; padding: 6px 0; font-size: 14px; }
    .total-line { font-size: 18px; font-weight: 700; }
    .payment-option { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border: 2px solid var(--border-light); border-radius: 8px; margin-bottom: 8px; cursor: pointer; transition: .2s; }
    .payment-option:hover { border-color: var(--primary-light, var(--primary)); }
    .payment-option input { margin-right: 4px; accent-color: var(--primary); }
    .payment-option i { font-size: 28px; }
    .payment-option:has(input:checked) { border-color: var(--primary); background: rgba(201,33,39,.04); }
    @media (max-width: 768px) {
        .cart-row { grid-template-columns: 80px 1fr; }
        .cart-qty, .cart-total { grid-column: 1 / -1; display: flex; justify-content: space-between; align-items: center; }
        .sticky-summary { position: static; }
    }
</style>
@endpush

@push('scripts')
<script>
$('#discount-form').on('submit', function(e) {
    e.preventDefault();
    const form = $(this);
    const code = $('#discount-code-input').val().trim();
    if (!code) return;

    $.ajax({
        url: '{{ route('discounts.apply') }}',
        type: 'POST',
        data: { code: code, _token: $('meta[name="csrf-token"]').attr('content') },
        success: function(res) {
            const msg = $('#discount-message');
            if (res.success) {
                msg.removeClass('text-danger').addClass('text-success').html('<i class="bi bi-check-circle me-1"></i>' + res.message);
                $('#discount-amount-row').show();
                $('#discount-amount').text('-' + res.discount.formatted_amount);
                $('#shipping-fee').text(res.discount.formatted_shipping);
                $('#total-amount').text(res.discount.formatted_total);
                setTimeout(() => location.reload(), 800);
            } else {
                msg.removeClass('text-success').addClass('text-danger').html('<i class="bi bi-x-circle me-1"></i>' + res.message);
            }
        },
        error: function() {
            $('#discount-message').addClass('text-danger').text('Lỗi áp dụng mã giảm giá');
        }
    });
});

$('#remove-discount-btn').on('click', function() {
    $.ajax({
        url: '{{ route('discounts.remove') }}',
        type: 'DELETE',
        data: { _token: $('meta[name="csrf-token"]').attr('content') },
        success: function() { location.reload(); }
    });
});
</script>
@endpush
@endsection
