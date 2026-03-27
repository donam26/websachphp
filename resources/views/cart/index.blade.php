@extends('layouts.app')

@section('title', 'Gio hang')

@section('content')
<div class="fs-section">
    <div class="container">
        <h1 class="fs-section-title" style="margin-bottom:2rem;">Gio hang</h1>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="fs-card">
                    @if($cartItems->isEmpty())
                        <div style="text-align:center;padding:4rem 2rem;">
                            <div style="width:80px;height:80px;border-radius:50%;background:var(--fs-surface);display:inline-flex;align-items:center;justify-content:center;margin-bottom:1.5rem;">
                                <i class="bi bi-bag-x" style="font-size:2rem;color:var(--fs-muted);"></i>
                            </div>
                            <h5 style="font-family:'Playfair Display',serif;margin-bottom:0.5rem;">Gio hang trong</h5>
                            <p style="color:var(--fs-muted);font-size:0.875rem;margin-bottom:1.5rem;">Hay kham pha nhung san pham tuyet voi cua chung toi</p>
                            <a href="{{ route('books.index') }}" class="fs-btn fs-btn-primary">
                                <i class="bi bi-arrow-left"></i> Tiep tuc mua sam
                            </a>
                        </div>
                    @else
                        <div style="padding:1.5rem;">
                            @foreach($cartItems as $item)
                            <div class="cart-item" style="{{ !$loop->last ? 'border-bottom:1px solid var(--fs-border);' : '' }}">
                                <div style="display:flex;gap:1rem;align-items:center;">
                                    <a href="{{ route('books.show', $item->book) }}">
                                        <img src="{{ asset('storage/books/' . $item->book->image) }}"
                                             alt="{{ $item->book->title }}"
                                             style="width:80px;height:100px;object-fit:cover;border-radius:10px;">
                                    </a>
                                    <div style="flex:1;min-width:0;">
                                        <h6 style="font-weight:600;margin-bottom:0.25rem;">
                                            <a href="{{ route('books.show', $item->book) }}" style="color:var(--fs-text);text-decoration:none;">
                                                {{ $item->book->title }}
                                            </a>
                                        </h6>
                                        <p style="color:var(--fs-muted);font-size:0.8rem;margin-bottom:0.4rem;">
                                            {{ $item->book->brand ?: $item->book->author }}
                                        </p>
                                        @if($item->size || $item->color)
                                        <div style="display:flex;gap:6px;">
                                            @if($item->size)
                                                <span class="fs-badge" style="background:var(--fs-surface);color:var(--fs-text);font-size:0.65rem;">Size: {{ $item->size }}</span>
                                            @endif
                                            @if($item->color)
                                                <span class="fs-badge" style="background:var(--fs-surface);color:var(--fs-text);font-size:0.65rem;">Mau: {{ $item->color }}</span>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div style="display:flex;align-items:center;gap:1.5rem;margin-top:1rem;justify-content:space-between;">
                                    <form action="{{ route('cart.update', $item) }}" method="POST" style="display:flex;align-items:center;">
                                        @csrf
                                        @method('PUT')
                                        <div class="qty-control-sm">
                                            <button class="qty-btn-sm" type="button"
                                                    onclick="this.parentNode.querySelector('input').stepDown(); this.closest('form').submit();">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <input type="number" class="qty-input-sm" name="quantity"
                                                   value="{{ $item->quantity }}" min="1" max="{{ $item->book->quantity }}"
                                                   onchange="this.form.submit()">
                                            <button class="qty-btn-sm" type="button"
                                                    onclick="this.parentNode.querySelector('input').stepUp(); this.closest('form').submit();">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                    </form>
                                    <div style="display:flex;align-items:center;gap:1.5rem;">
                                        <span style="font-family:'Playfair Display',serif;font-weight:700;font-size:1.05rem;">
                                            {{ number_format($item->book->price * $item->quantity) }}d
                                        </span>
                                        <form action="{{ route('cart.remove', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="border:none;background:none;color:var(--fs-muted);cursor:pointer;padding:6px;transition:color 0.3s;">
                                                <i class="bi bi-trash3" style="font-size:1rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div style="padding:0 1.5rem 1.5rem;display:flex;justify-content:space-between;align-items:center;">
                            <a href="{{ route('books.index') }}" class="fs-btn fs-btn-outline" style="font-size:0.78rem;">
                                <i class="bi bi-arrow-left"></i> Tiep tuc mua sam
                            </a>
                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="fs-btn" style="background:#fce4ec;color:#c62828;border-color:#fce4ec;font-size:0.78rem;">
                                    <i class="bi bi-trash3"></i> Xoa gio hang
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-4">
                <div class="fs-card" style="position:sticky;top:100px;">
                    <div style="padding:1.5rem;">
                        <h5 style="font-family:'Playfair Display',serif;font-weight:600;margin-bottom:1.25rem;">Tong don hang</h5>

                        @if(!$cartItems->isEmpty())
                            <form id="discount-form" action="{{ route('discounts.apply') }}" method="POST" style="margin-bottom:1.25rem;">
                                @csrf
                                <div style="display:flex;gap:8px;">
                                    <input type="text" name="code" class="form-control" style="flex:1;"
                                           placeholder="Ma giam gia" value="{{ session('applied_discount.code') ?? '' }}">
                                    <button class="fs-btn fs-btn-outline" type="submit" style="padding:0.5rem 1rem;white-space:nowrap;">
                                        Ap dung
                                    </button>
                                </div>
                                <div id="discount-message" class="small mt-2"></div>
                                @if(session('applied_discount'))
                                    <div class="text-success small mt-2" style="font-weight:500;">
                                        <i class="bi bi-check-circle"></i> Da ap dung: {{ session('applied_discount.code') }}
                                    </div>
                                @endif
                            </form>
                        @endif

                        <div style="display:flex;justify-content:space-between;margin-bottom:0.75rem;font-size:0.9rem;">
                            <span style="color:var(--fs-muted);">Tam tinh</span>
                            <span id="subtotal" style="font-weight:500;">{{ number_format($subtotal) }}d</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;margin-bottom:0.75rem;font-size:0.9rem;">
                            <span style="color:var(--fs-muted);">Phi van chuyen</span>
                            <span style="font-weight:500;">30,000d</span>
                        </div>
                        <div id="discount-amount-row" style="display:{{ session('applied_discount') ? 'flex' : 'none' }};justify-content:space-between;margin-bottom:0.75rem;font-size:0.9rem;">
                            <span style="color:var(--fs-muted);">Giam gia</span>
                            <span style="color:var(--fs-danger);font-weight:500;" id="discount-amount">-{{ number_format(session('applied_discount.amount') ?? 0) }}d</span>
                        </div>

                        <div style="border-top:1px solid var(--fs-border);padding-top:1rem;margin-top:1rem;display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-weight:600;">Tong cong</span>
                            <span style="font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:var(--fs-primary);" id="total-amount">
                                {{ number_format($subtotal + 30000 - (session('applied_discount.amount') ?? 0)) }}d
                            </span>
                        </div>

                        @if(!$cartItems->isEmpty())
                        <form action="{{ route('orders.checkout') }}" method="POST" style="margin-top:1.5rem;">
                            @csrf
                            <div style="margin-bottom:1.25rem;">
                                <label class="form-label">Thong tin giao hang</label>
                                <input type="text" class="form-control mb-2" name="shipping_name"
                                       value="{{ auth()->user()->full_name }}" placeholder="Ho ten nguoi nhan" required>
                                <input type="text" class="form-control mb-2" name="shipping_phone"
                                       value="{{ auth()->user()->phone_number }}" placeholder="So dien thoai" required>
                                <textarea class="form-control mb-2" name="shipping_address"
                                          placeholder="Dia chi giao hang" rows="2" required>{{ auth()->user()->address }}</textarea>
                                <textarea class="form-control" name="note" placeholder="Ghi chu (khong bat buoc)" rows="2"></textarea>
                            </div>

                            <div style="margin-bottom:1.25rem;">
                                <label class="form-label">Phuong thuc thanh toan</label>
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="cod" checked>
                                    <div class="payment-option-inner">
                                        <i class="bi bi-cash-coin" style="font-size:1.2rem;color:var(--fs-accent);"></i>
                                        <div>
                                            <div style="font-weight:600;font-size:0.85rem;">COD</div>
                                            <div style="font-size:0.75rem;color:var(--fs-muted);">Thanh toan khi nhan hang</div>
                                        </div>
                                    </div>
                                </label>
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="vnpay">
                                    <div class="payment-option-inner">
                                        <i class="bi bi-credit-card" style="font-size:1.2rem;color:var(--fs-accent);"></i>
                                        <div>
                                            <div style="font-weight:600;font-size:0.85rem;">VNPAY</div>
                                            <div style="font-size:0.75rem;color:var(--fs-muted);">Thanh toan truc tuyen</div>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <button type="submit" class="fs-btn fs-btn-accent w-100" style="justify-content:center;padding:0.85rem;">
                                <i class="bi bi-lock"></i> Thanh toan
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.cart-item { padding: 1.25rem 0; }
.cart-item:first-child { padding-top: 0; }

.qty-control-sm {
    display: flex;
    align-items: center;
    border: 1.5px solid var(--fs-border);
    border-radius: 8px;
    overflow: hidden;
}

.qty-btn-sm {
    width: 34px;
    height: 34px;
    border: none;
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: var(--fs-text);
    transition: background 0.2s;
}

.qty-btn-sm:hover { background: var(--fs-surface); }

.qty-input-sm {
    width: 38px;
    text-align: center;
    border: none;
    font-weight: 600;
    font-size: 0.85rem;
    background: transparent;
    -moz-appearance: textfield;
}

.qty-input-sm::-webkit-inner-spin-button,
.qty-input-sm::-webkit-outer-spin-button { -webkit-appearance: none; }

.payment-option {
    display: block;
    cursor: pointer;
    margin-bottom: 8px;
}

.payment-option input { display: none; }

.payment-option-inner {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0.75rem 1rem;
    border: 1.5px solid var(--fs-border);
    border-radius: var(--fs-radius-sm);
    transition: var(--fs-transition);
}

.payment-option input:checked + .payment-option-inner {
    border-color: var(--fs-accent);
    background: rgba(201, 169, 110, 0.06);
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('#discount-form').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                if (response.success) {
                    $('#discount-message').removeClass('text-danger').addClass('text-success').text(response.message);
                    $('#discount-amount-row').show();
                    $('#discount-amount').text('-' + response.discount.formatted_amount);
                    $('#total-amount').text(response.discount.formatted_total);
                } else {
                    $('#discount-message').removeClass('text-success').addClass('text-danger').text(response.message);
                    $('#discount-amount-row').hide();
                    $('#total-amount').text('{{ number_format($subtotal + 30000) }}d');
                }
            },
            error: function() {
                $('#discount-message').removeClass('text-success').addClass('text-danger').text('Da co loi xay ra');
            }
        });
    });
});
</script>
@endpush
@endsection
