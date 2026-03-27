@extends('layouts.admin')

@section('title', 'Chi tiet don hang #' . $order->id)

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
    <div style="display:flex;align-items:center;gap:1rem;">
        <a href="{{ route('admin.orders.index') }}" class="ad-btn ad-btn-outline ad-btn-icon" style="width:40px;height:40px;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 style="font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:var(--ad-primary);margin:0;">
            Don hang #{{ $order->id }}
        </h1>
    </div>
    <button class="ad-btn ad-btn-outline" onclick="window.print()">
        <i class="bi bi-printer"></i> In don hang
    </button>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Order Items -->
        <div class="ad-card mb-4">
            <div class="ad-card-header">
                <h5>Chi tiet san pham</h5>
            </div>
            <div style="overflow-x:auto;">
                <table class="ad-table">
                    <thead>
                        <tr>
                            <th>San pham</th>
                            <th style="text-align:center;">So luong</th>
                            <th style="text-align:right;">Don gia</th>
                            <th style="text-align:right;">Thanh tien</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <img src="{{ asset('storage/books/'.$item->book->image) }}" alt="{{ $item->book->title }}"
                                         style="width:48px;height:60px;object-fit:cover;border-radius:8px;border:1px solid var(--ad-border);">
                                    <div>
                                        <div style="font-weight:500;">{{ $item->book->title }}</div>
                                        <small style="color:var(--ad-muted);">{{ $item->book->brand ?: $item->book->author }}</small>
                                        @if($item->size || $item->color)
                                        <div style="margin-top:4px;display:flex;gap:4px;">
                                            @if($item->size)
                                                <span class="ad-badge" style="background:var(--ad-surface);color:var(--ad-text);font-size:0.65rem;">Size: {{ $item->size }}</span>
                                            @endif
                                            @if($item->color)
                                                <span class="ad-badge" style="background:var(--ad-surface);color:var(--ad-text);font-size:0.65rem;">Mau: {{ $item->color }}</span>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="text-align:center;">{{ $item->quantity }}</td>
                            <td style="text-align:right;white-space:nowrap;">{{ number_format($item->price) }}d</td>
                            <td style="text-align:right;white-space:nowrap;font-weight:600;">{{ number_format($item->price * $item->quantity) }}d</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align:right;font-weight:600;border-top:2px solid var(--ad-border);">Tong tien:</td>
                            <td style="text-align:right;font-family:'Playfair Display',serif;font-weight:700;font-size:1.1rem;border-top:2px solid var(--ad-border);">
                                {{ number_format($order->total_amount) }}d
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Order History -->
        <div class="ad-card">
            <div class="ad-card-header"><h5>Lich su don hang</h5></div>
            <div class="ad-card-body">
                @foreach($order->history as $history)
                <div class="timeline-row">
                    <div class="timeline-dot"></div>
                    <div class="timeline-body">
                        <div style="font-size:0.78rem;color:var(--ad-muted);margin-bottom:2px;">
                            {{ $history->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div style="font-weight:600;font-size:0.9rem;">{{ $history->status }}</div>
                        @if($history->note)
                        <div style="color:var(--ad-muted);font-size:0.85rem;margin-top:2px;">{{ $history->note }}</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Customer Info -->
        <div class="ad-card mb-4">
            <div class="ad-card-header"><h5>Khach hang</h5></div>
            <div class="ad-card-body">
                <div style="display:flex;flex-direction:column;gap:0.6rem;font-size:0.875rem;">
                    <div><span style="color:var(--ad-muted);">Ho ten:</span> <strong>{{ $order->user->full_name }}</strong></div>
                    <div><span style="color:var(--ad-muted);">Email:</span> {{ $order->user->email }}</div>
                    <div><span style="color:var(--ad-muted);">SDT:</span> {{ $order->user->phone_number }}</div>
                    <div><span style="color:var(--ad-muted);">Dia chi:</span> {{ $order->user->address }}</div>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="ad-card mb-4">
            <div class="ad-card-header"><h5>Thanh toan</h5></div>
            <div class="ad-card-body">
                <div style="display:flex;flex-direction:column;gap:0.6rem;font-size:0.875rem;">
                    <div>
                        <span style="color:var(--ad-muted);">Phuong thuc:</span>
                        <strong>{{ $order->payment_method === 'cod' ? 'COD' : 'VNPay' }}</strong>
                    </div>
                    <div>
                        <span style="color:var(--ad-muted);">Trang thai:</span>
                        @switch($order->payment_status)
                            @case('pending')
                                <span class="ad-badge ad-badge-warning">Chua thanh toan</span>
                                @break
                            @case('completed')
                                <span class="ad-badge ad-badge-success">Da thanh toan</span>
                                @break
                            @case('failed')
                                <span class="ad-badge ad-badge-danger">That bai</span>
                                @break
                        @endswitch
                    </div>
                    @if($order->payment_method == 'vnpay' && $order->transaction_id)
                    <div><span style="color:var(--ad-muted);">Ma GD:</span> <code>{{ $order->transaction_id }}</code></div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Update Status -->
        <div class="ad-card">
            <div class="ad-card-header"><h5>Cap nhat trang thai</h5></div>
            <div class="ad-card-body">
                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Trang thai</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Cho xac nhan</option>
                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Da xac nhan</option>
                            <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Dang giao</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Da giao</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Da huy</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chu</label>
                        <textarea name="note" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="ad-btn ad-btn-primary w-100" style="justify-content:center;">
                        <i class="bi bi-check-lg"></i> Cap nhat
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.timeline-row {
    display: flex;
    gap: 16px;
    position: relative;
    padding-bottom: 1.25rem;
    margin-bottom: 1.25rem;
}

.timeline-row:not(:last-child) {
    border-bottom: 1px solid var(--ad-border);
}

.timeline-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--ad-accent);
    flex-shrink: 0;
    margin-top: 4px;
    box-shadow: 0 0 0 4px rgba(201, 169, 110, 0.2);
}

.timeline-body { flex: 1; }
</style>
@endsection
