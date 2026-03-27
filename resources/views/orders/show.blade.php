@extends('layouts.app')

@section('title', 'Chi tiet don hang #' . $order->id)

@section('content')
<div class="fs-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
                    <div>
                        <h1 class="fs-section-title" style="margin-bottom:0.25rem;">Don hang #{{ $order->id }}</h1>
                        <p style="color:var(--fs-muted);font-size:0.875rem;margin:0;">Dat ngay {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <a href="{{ route('orders.index') }}" class="fs-btn fs-btn-outline" style="font-size:0.78rem;">
                        <i class="bi bi-arrow-left"></i> Quay lai
                    </a>
                </div>

                <!-- Order Info -->
                <div class="row g-4" style="margin-bottom:1.5rem;">
                    <div class="col-md-6">
                        <div class="fs-card" style="height:100%;">
                            <div style="padding:1.5rem;">
                                <h6 style="font-family:'Playfair Display',serif;font-weight:600;margin-bottom:1rem;">
                                    <i class="bi bi-info-circle" style="color:var(--fs-accent);"></i> Thong tin don hang
                                </h6>
                                <div style="display:flex;flex-direction:column;gap:0.75rem;">
                                    <div style="display:flex;justify-content:space-between;align-items:center;">
                                        <span style="color:var(--fs-muted);font-size:0.875rem;">Trang thai</span>
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="fs-badge fs-badge-warning">Cho xac nhan</span>
                                                @break
                                            @case('confirmed')
                                                <span class="fs-badge fs-badge-info">Da xac nhan</span>
                                                @break
                                            @case('shipping')
                                                <span class="fs-badge" style="background:#e3f2fd;color:#1565c0;">Dang giao</span>
                                                @break
                                            @case('completed')
                                                <span class="fs-badge fs-badge-success">Da giao</span>
                                                @break
                                            @case('cancelled')
                                                <span class="fs-badge fs-badge-danger">Da huy</span>
                                                @break
                                        @endswitch
                                    </div>
                                    <div style="display:flex;justify-content:space-between;">
                                        <span style="color:var(--fs-muted);font-size:0.875rem;">Ngay dat</span>
                                        <span style="font-weight:500;font-size:0.875rem;">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div style="display:flex;justify-content:space-between;">
                                        <span style="color:var(--fs-muted);font-size:0.875rem;">Thanh toan</span>
                                        <span style="font-weight:500;font-size:0.875rem;">
                                            {{ $order->payment_method === 'cod' ? 'Thanh toan khi nhan hang' : 'VNPay' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fs-card" style="height:100%;">
                            <div style="padding:1.5rem;">
                                <h6 style="font-family:'Playfair Display',serif;font-weight:600;margin-bottom:1rem;">
                                    <i class="bi bi-truck" style="color:var(--fs-accent);"></i> Thong tin giao hang
                                </h6>
                                <p style="color:var(--fs-muted);font-size:0.875rem;line-height:1.7;margin:0;">
                                    {{ $order->shipping_address }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="fs-card">
                    <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--fs-border);">
                        <h6 style="font-family:'Playfair Display',serif;font-weight:600;margin:0;">
                            <i class="bi bi-box-seam" style="color:var(--fs-accent);"></i> Chi tiet san pham
                        </h6>
                    </div>
                    <div style="padding:1.5rem;">
                        @foreach($order->orderItems as $item)
                        <div style="display:flex;align-items:center;gap:1rem;{{ !$loop->last ? 'padding-bottom:1.25rem;margin-bottom:1.25rem;border-bottom:1px solid var(--fs-border);' : '' }}">
                            <img src="{{ asset('storage/books/'.$item->book->image) }}"
                                 alt="{{ $item->book->title }}"
                                 style="width:70px;height:90px;object-fit:cover;border-radius:10px;">
                            <div style="flex:1;min-width:0;">
                                <h6 style="font-weight:600;margin-bottom:0.2rem;font-size:0.9rem;">{{ $item->book->title }}</h6>
                                <p style="color:var(--fs-muted);font-size:0.8rem;margin-bottom:0.35rem;">{{ $item->book->brand ?: $item->book->author }}</p>
                                @if($item->size || $item->color)
                                <div style="display:flex;gap:6px;">
                                    @if($item->size)
                                        <span class="fs-badge" style="background:var(--fs-surface);color:var(--fs-text);font-size:0.6rem;">Size: {{ $item->size }}</span>
                                    @endif
                                    @if($item->color)
                                        <span class="fs-badge" style="background:var(--fs-surface);color:var(--fs-text);font-size:0.6rem;">Mau: {{ $item->color }}</span>
                                    @endif
                                </div>
                                @endif
                            </div>
                            <div style="text-align:right;white-space:nowrap;">
                                <div style="font-size:0.8rem;color:var(--fs-muted);">{{ number_format($item->price) }}d x {{ $item->quantity }}</div>
                                <div style="font-family:'Playfair Display',serif;font-weight:700;margin-top:2px;">
                                    {{ number_format($item->price * $item->quantity) }}d
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Summary -->
                    <div style="border-top:1px solid var(--fs-border);padding:1.5rem;background:var(--fs-surface);">
                        <div style="max-width:300px;margin-left:auto;">
                            <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;font-size:0.875rem;">
                                <span style="color:var(--fs-muted);">Tam tinh</span>
                                <span>{{ number_format($order->total_amount - 30000) }}d</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;margin-bottom:0.75rem;font-size:0.875rem;">
                                <span style="color:var(--fs-muted);">Van chuyen</span>
                                <span>30,000d</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;padding-top:0.75rem;border-top:1px solid var(--fs-border);">
                                <span style="font-weight:600;">Tong cong</span>
                                <span style="font-family:'Playfair Display',serif;font-size:1.25rem;font-weight:700;color:var(--fs-primary);">
                                    {{ number_format($order->total_amount) }}d
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
