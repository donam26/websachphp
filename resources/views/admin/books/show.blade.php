@extends('layouts.admin')

@section('title', 'Chi tiết: ' . $book->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h5 class="mb-0"><i class="bi bi-book me-2 text-primary"></i>Chi tiết sách</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Quay lại</a>
        <a href="{{ route('books.show', $book) }}" target="_blank" class="btn btn-outline-info btn-sm"><i class="bi bi-box-arrow-up-right me-1"></i>Xem trên web</a>
        <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Sửa</a>
    </div>
</div>

{{-- Thống kê --}}
<div class="row g-3 mb-1">
    <div class="col-md-6 col-xl-3">
        <div class="stat-card success">
            <i class="bi bi-bag-check stat-icon"></i>
            <div class="stat-label">Đã bán</div>
            <div class="stat-value">{{ number_format($soldQuantity, 0, ',', '.') }}</div>
            <div class="stat-foot">Từ đơn đã hoàn thành</div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card primary">
            <i class="bi bi-currency-dollar stat-icon"></i>
            <div class="stat-label">Doanh thu</div>
            <div class="stat-value">{{ number_format($revenue, 0, ',', '.') }}đ</div>
            <div class="stat-foot">Tổng từ sách này</div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card warning">
            <i class="bi bi-box-seam stat-icon"></i>
            <div class="stat-label">Tồn kho hiện tại</div>
            <div class="stat-value">{{ number_format($book->quantity, 0, ',', '.') }}</div>
            <div class="stat-foot">Đã nhập tổng: {{ number_format($totalImported, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card info">
            <i class="bi bi-star stat-icon"></i>
            <div class="stat-label">Đánh giá TB</div>
            <div class="stat-value">{{ number_format($book->average_rating, 1) }}/5</div>
            <div class="stat-foot">{{ $book->reviews()->count() }} lượt đánh giá</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ $book->image_url }}"
                     onerror="this.src='https://placehold.co/300x400/f4f6f8/4f46e5?text=Book'"
                     style="max-width:100%;max-height:340px;object-fit:contain;border-radius:10px;">
                <div class="mt-3">
                    @if($book->is_available)
                        <span class="badge badge-soft-success">Đang bán</span>
                    @elseif($book->quantity === 0)
                        <span class="badge badge-soft-danger">Hết hàng</span>
                    @else
                        <span class="badge badge-soft-secondary">Ngừng bán</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header bg-white"><strong>Thông tin sách</strong></div>
            <div class="card-body">
                <h5 class="mb-3">{{ $book->title }}</h5>
                <table class="table table-sm">
                    <tbody>
                        <tr><th width="160" class="text-muted">Tác giả</th><td>{{ $book->author_names ?: '—' }}</td></tr>
                        <tr><th class="text-muted">Danh mục</th><td>{{ $book->category->name ?? 'Chưa phân loại' }}</td></tr>
                        <tr><th class="text-muted">ISBN</th><td>{{ $book->isbn ?: '—' }}</td></tr>
                        <tr><th class="text-muted">Năm xuất bản</th><td>{{ $book->publish_year ?: '—' }}</td></tr>
                        <tr><th class="text-muted">Giá bán</th><td class="fw-bold text-primary">{{ number_format($book->price, 0, ',', '.') }}đ</td></tr>
                        <tr><th class="text-muted">Tồn kho</th><td>{{ $book->quantity }} cuốn</td></tr>
                        <tr><th class="text-muted">Ngày tạo</th><td>{{ $book->created_at->format('d/m/Y H:i') }}</td></tr>
                    </tbody>
                </table>
                @if($book->description)
                    <div class="mt-2">
                        <div class="text-muted mb-1">Mô tả</div>
                        <div style="white-space:pre-line;">{{ $book->description }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    {{-- Đánh giá gần đây --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white"><strong><i class="bi bi-chat-square-text me-2 text-primary"></i>Đánh giá gần đây</strong></div>
            <div class="card-body p-0">
                @forelse($recentReviews as $review)
                    <div class="px-3 py-2 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">{{ $review->user->full_name ?? $review->user->username ?? 'Ẩn danh' }}</span>
                            <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                        </div>
                        <div style="font-size:13px;">@include('books._stars', ['rating' => $review->rating])</div>
                        @if($review->comment)<div class="small text-muted">{{ $review->comment }}</div>@endif
                    </div>
                @empty
                    <div class="text-center text-muted py-4">Chưa có đánh giá nào</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Lịch sử nhập hàng --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <strong><i class="bi bi-box-arrow-in-down me-2 text-primary"></i>Lịch sử nhập hàng</strong>
                <a href="{{ route('admin.stock-imports.create') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg me-1"></i>Nhập thêm</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Mã phiếu</th>
                                <th>Ngày</th>
                                <th class="text-center">SL</th>
                                <th class="text-end pe-3">Giá nhập</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($importItems as $item)
                                <tr>
                                    <td class="ps-3"><a href="{{ route('admin.stock-imports.show', $item->stock_import_id) }}" class="text-decoration-none">{{ $item->stockImport->code ?? '—' }}</a></td>
                                    <td><small>{{ optional($item->stockImport->imported_at ?? $item->created_at)->format('d/m/Y') }}</small></td>
                                    <td class="text-center">+{{ $item->quantity }}</td>
                                    <td class="text-end pe-3">{{ number_format($item->import_price, 0, ',', '.') }}đ</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">Chưa có lịch sử nhập hàng</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
