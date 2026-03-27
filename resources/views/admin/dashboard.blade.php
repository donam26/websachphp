@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
    <div>
        <h1 style="font-family:'Playfair Display',serif;font-size:1.75rem;font-weight:700;color:var(--ad-primary);margin:0;">
            Tong quan
        </h1>
        <p style="color:var(--ad-muted);font-size:0.85rem;margin:0.25rem 0 0;">
            Chao mung quay tro lai, {{ Auth::user()->full_name ?? Auth::user()->username }}
        </p>
    </div>
    <button class="ad-btn ad-btn-outline" onclick="window.print()">
        <i class="bi bi-printer"></i> In bao cao
    </button>
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="ad-stat">
            <div class="ad-stat-icon green"><i class="bi bi-wallet2"></i></div>
            <div>
                <div class="ad-stat-label">Tong doanh thu</div>
                <div class="ad-stat-value">{{ number_format($totalRevenue ?? 0) }}d</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="ad-stat">
            <div class="ad-stat-icon blue"><i class="bi bi-box-seam"></i></div>
            <div>
                <div class="ad-stat-label">Don cho xu ly</div>
                <div class="ad-stat-value">{{ $newOrders ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="ad-stat">
            <div class="ad-stat-icon purple"><i class="bi bi-handbag"></i></div>
            <div>
                <div class="ad-stat-label">Tong san pham</div>
                <div class="ad-stat-value">{{ $totalBooks ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="ad-stat">
            <div class="ad-stat-icon amber"><i class="bi bi-people"></i></div>
            <div>
                <div class="ad-stat-label">Nguoi dung</div>
                <div class="ad-stat-value">{{ $totalUsers ?? 0 }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Tables -->
<div class="row g-4">
    <div class="col-lg-6">
        <div class="ad-card">
            <div class="ad-card-header">
                <h5>San pham moi cap nhat</h5>
                <span class="ad-badge ad-badge-info">Top 20</span>
            </div>
            <div style="padding:0;">
                <table class="ad-table">
                    <thead>
                        <tr>
                            <th>San pham</th>
                            <th>Gia</th>
                            <th>Cap nhat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($Topproducts as $products)
                        <tr>
                            <td>
                                <a href="{{ route('admin.products.show', $products->code) }}"
                                   style="text-decoration:none;color:var(--ad-text);font-weight:500;" target="_blank">
                                    {{ Str::limit($products->title, 35) }}
                                </a>
                            </td>
                            <td style="white-space:nowrap;font-weight:500;">{{ number_format($products->price) }}d</td>
                            <td style="font-size:0.8rem;color:var(--ad-muted);white-space:nowrap;">{{ $products->updated_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="ad-card">
            <div class="ad-card-header">
                <h5>San pham gia tri cao</h5>
                <a href="{{ route('admin.products.index') }}" class="ad-btn ad-btn-accent ad-btn-sm">Xem tat ca</a>
            </div>
            <div style="padding:0;">
                <table class="ad-table">
                    <thead>
                        <tr>
                            <th>San pham</th>
                            <th>Gia</th>
                            <th>Ngay tao</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($Topprice as $item)
                        <tr>
                            <td style="font-weight:500;">{{ Str::limit($item->title, 35) }}</td>
                            <td style="white-space:nowrap;font-weight:500;">{{ number_format($item->price) }}d</td>
                            <td style="font-size:0.8rem;color:var(--ad-muted);white-space:nowrap;">{{ $item->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
