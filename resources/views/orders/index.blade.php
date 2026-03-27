@extends('layouts.app')

@section('title', 'Don hang cua toi')

@section('content')
<div class="fs-section">
    <div class="container">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
            <h1 class="fs-section-title">Don hang cua toi</h1>
            <a href="{{ route('books.index') }}" class="fs-btn fs-btn-primary" style="font-size:0.78rem;">
                <i class="bi bi-bag-plus"></i> Mua sam them
            </a>
        </div>

        @if($orders->count() > 0)
            <div class="row g-4">
                @foreach($orders as $order)
                <div class="col-md-6">
                    <div class="fs-card order-card" style="height:100%;">
                        <div style="padding:1.5rem;">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
                                <span style="font-family:'Playfair Display',serif;font-weight:700;font-size:1.05rem;">
                                    #{{ $order->id }}
                                </span>
                                @switch($order->status)
                                    @case('pending')
                                        <span class="fs-badge fs-badge-warning"><i class="bi bi-clock me-1"></i> Cho xac nhan</span>
                                        @break
                                    @case('confirmed')
                                        <span class="fs-badge fs-badge-info"><i class="bi bi-check-circle me-1"></i> Da xac nhan</span>
                                        @break
                                    @case('shipping')
                                        <span class="fs-badge" style="background:#e3f2fd;color:#1565c0;"><i class="bi bi-truck me-1"></i> Dang giao</span>
                                        @break
                                    @case('completed')
                                        <span class="fs-badge fs-badge-success"><i class="bi bi-check2-all me-1"></i> Da giao</span>
                                        @break
                                    @case('cancelled')
                                        <span class="fs-badge fs-badge-danger"><i class="bi bi-x-circle me-1"></i> Da huy</span>
                                        @break
                                @endswitch
                            </div>

                            <div style="display:flex;flex-direction:column;gap:0.5rem;margin-bottom:1.25rem;padding-bottom:1.25rem;border-bottom:1px solid var(--fs-border);">
                                <div style="display:flex;justify-content:space-between;font-size:0.85rem;">
                                    <span style="color:var(--fs-muted);display:flex;align-items:center;gap:6px;">
                                        <i class="bi bi-calendar3"></i> {{ $order->created_at->format('d/m/Y H:i') }}
                                    </span>
                                    <span style="color:var(--fs-muted);display:flex;align-items:center;gap:6px;">
                                        <i class="bi bi-credit-card"></i>
                                        {{ $order->payment_method === 'cod' ? 'COD' : 'VNPay' }}
                                    </span>
                                </div>
                                <div style="font-size:0.85rem;color:var(--fs-muted);display:flex;align-items:center;gap:6px;">
                                    <i class="bi bi-geo-alt"></i> {{ Str::limit($order->shipping_address, 50) }}
                                </div>
                            </div>

                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <div>
                                    <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;color:var(--fs-muted);margin-bottom:2px;">Tong tien</div>
                                    <div style="font-family:'Playfair Display',serif;font-size:1.25rem;font-weight:700;color:var(--fs-primary);">
                                        {{ number_format($order->total_amount) }}d
                                    </div>
                                </div>
                                <a href="{{ route('orders.show', $order) }}" class="fs-btn fs-btn-outline" style="font-size:0.78rem;">
                                    Xem chi tiet <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center" style="margin-top:2.5rem;">
                {{ $orders->links() }}
            </div>
        @else
            <div class="fs-card" style="text-align:center;padding:4rem 2rem;">
                <div style="width:80px;height:80px;border-radius:50%;background:var(--fs-surface);display:inline-flex;align-items:center;justify-content:center;margin-bottom:1.5rem;">
                    <i class="bi bi-box-seam" style="font-size:2rem;color:var(--fs-muted);"></i>
                </div>
                <h5 style="font-family:'Playfair Display',serif;margin-bottom:0.5rem;">Chua co don hang nao</h5>
                <p style="color:var(--fs-muted);font-size:0.875rem;margin-bottom:1.5rem;">Hay kham pha nhung san pham thoi trang moi nhat</p>
                <a href="{{ route('books.index') }}" class="fs-btn fs-btn-primary">
                    <i class="bi bi-bag-plus"></i> Mua sam ngay
                </a>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.order-card { transition: var(--fs-transition); }
.order-card:hover { transform: translateY(-4px); box-shadow: var(--fs-shadow-hover); }
</style>
@endpush
@endsection
