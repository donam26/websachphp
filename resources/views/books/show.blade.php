@extends('layouts.app')

@section('title', $book->title)

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="{{ route('books.index') }}" class="text-decoration-none">Sách</a></li>
        @if($book->category)
            <li class="breadcrumb-item">
                <a href="{{ route('books.category', $book->category->slug) }}" class="text-decoration-none">{{ $book->category->name }}</a>
            </li>
        @endif
        <li class="breadcrumb-item active text-truncate" style="max-width:300px;">{{ $book->title }}</li>
    </ol>
</nav>

<div class="card mb-4">
    <div class="card-body">
        <div class="row g-4">
            {{-- Book image --}}
            <div class="col-md-4">
                <div class="book-image-wrap">
                    <img src="{{ $book->image_url }}"
                         alt="{{ $book->title }}" class="img-fluid main-image"
                         onerror="this.src='https://placehold.co/400x550/eef2ff/4f46e5?text=Book'">
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-outline-danger btn-sm flex-fill">
                        <i class="bi bi-heart me-1"></i> Yêu thích
                    </button>
                    <button class="btn btn-outline-primary btn-sm flex-fill" onclick="navigator.share && navigator.share({url:location.href,title:'{{ addslashes($book->title) }}'})">
                        <i class="bi bi-share me-1"></i> Chia sẻ
                    </button>
                </div>
            </div>

            {{-- Book info --}}
            <div class="col-md-8">
                @if($book->category)
                    <a href="{{ route('books.category', $book->category->slug) }}" class="badge badge-soft-primary text-decoration-none">
                        <i class="bi bi-bookmark-fill me-1"></i>{{ $book->category->name }}
                    </a>
                @endif
                <h1 class="book-title mt-2">{{ $book->title }}</h1>
                <div class="d-flex flex-wrap gap-3 text-muted small mb-3">
                    <span><i class="bi bi-person-circle me-1"></i>Tác giả: <strong class="text-dark">{{ $book->author_names }}</strong></span>
                    <span class="text-warning">
                        @include('books._stars', ['rating' => $book->average_rating])
                        <span class="text-muted ms-1">@if($book->reviews->count()){{ number_format($book->average_rating, 1) }} ({{ $book->reviews->count() }} đánh giá)@else(Chưa có đánh giá)@endif</span>
                    </span>
                    <span><i class="bi bi-bag-check me-1"></i>Đã bán: {{ $book->orderItems->sum('quantity') }}</span>
                </div>

                <div class="price-box">
                    <span class="current-price">{{ number_format($book->price, 0, ',', '.') }}đ</span>
                </div>

                <div class="info-table mb-3">
                    <div class="info-row">
                        <span class="info-label">Tình trạng:</span>
                        <span class="info-value">
                            @if($book->quantity > 0)
                                <span class="badge badge-soft-success">Còn hàng ({{ $book->quantity }})</span>
                            @else
                                <span class="badge badge-soft-danger">Hết hàng</span>
                            @endif
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Mã sản phẩm:</span>
                        <span class="info-value">BK-{{ str_pad($book->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Vận chuyển:</span>
                        <span class="info-value">Freeship đơn từ 250.000đ - Giao 2-5 ngày</span>
                    </div>
                </div>

                @auth
                    @if($book->is_available)
                    <form action="{{ route('cart.add') }}" method="POST" class="mb-3">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <label class="text-muted">Số lượng:</label>
                            <div class="quantity-control">
                                <button class="btn-qty" type="button" onclick="decQty()"><i class="bi bi-dash"></i></button>
                                <input type="number" id="qty" name="quantity" value="1" min="1" max="{{ $book->quantity }}">
                                <button class="btn-qty" type="button" onclick="incQty()"><i class="bi bi-plus"></i></button>
                            </div>
                            <small class="text-muted">(Còn {{ $book->quantity }} sản phẩm)</small>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-outline-primary btn-lg flex-fill">
                                <i class="bi bi-cart-plus me-2"></i>Thêm vào giỏ hàng
                            </button>
                            <button type="submit" name="buy_now" value="1" class="btn btn-primary btn-lg flex-fill">
                                <i class="bi bi-lightning-charge-fill me-2"></i>Mua ngay
                            </button>
                        </div>
                    </form>
                    @else
                        <button class="btn btn-secondary btn-lg w-100 mb-3" disabled>
                            <i class="bi bi-x-circle me-2"></i>Hết hàng
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100 mb-3">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập để mua sách
                    </a>
                @endauth

                <div class="benefit-list">
                    <div class="benefit-item"><i class="bi bi-truck text-primary"></i> Giao hàng toàn quốc</div>
                    <div class="benefit-item"><i class="bi bi-shield-check text-success"></i> Sách chính hãng 100%</div>
                    <div class="benefit-item"><i class="bi bi-arrow-counterclockwise text-warning"></i> Đổi trả trong 7 ngày</div>
                    <div class="benefit-item"><i class="bi bi-credit-card text-info"></i> Thanh toán đa dạng</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Description tabs --}}
<div class="card mb-4">
    <div class="card-body">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-desc">Mô tả sản phẩm</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-detail">Thông tin chi tiết</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-review">Đánh giá</button></li>
        </ul>
        <div class="tab-content pt-4">
            <div class="tab-pane fade show active" id="tab-desc">
                <div class="book-description">{!! nl2br(e($book->description)) !!}</div>
            </div>
            <div class="tab-pane fade" id="tab-detail">
                <table class="table table-striped">
                    <tr><th width="200">Tên sách</th><td>{{ $book->title }}</td></tr>
                    <tr><th>Tác giả</th><td>{{ $book->author_names }}</td></tr>
                    @if($book->isbn)<tr><th>ISBN</th><td>{{ $book->isbn }}</td></tr>@endif
                    @if($book->publish_year)<tr><th>Năm xuất bản</th><td>{{ $book->publish_year }}</td></tr>@endif
                    <tr><th>Thể loại</th><td>{{ $book->category->name ?? 'Chưa phân loại' }}</td></tr>
                    <tr><th>Giá bán</th><td>{{ number_format($book->price) }}đ</td></tr>
                    <tr><th>Trạng thái</th><td>{{ $book->quantity > 0 ? 'Còn hàng' : 'Hết hàng' }}</td></tr>
                </table>
            </div>
            <div class="tab-pane fade" id="tab-review">
                {{-- Tổng quan đánh giá --}}
                <div class="d-flex flex-wrap align-items-center gap-4 mb-4">
                    <div class="text-center px-3">
                        <div class="display-5 fw-bold text-warning lh-1">{{ number_format($book->average_rating, 1) }}</div>
                        <div class="mt-1">@include('books._stars', ['rating' => $book->average_rating])</div>
                        <div class="text-muted small mt-1">{{ $book->reviews->count() }} đánh giá</div>
                    </div>
                </div>

                {{-- Form gửi / cập nhật đánh giá --}}
                @auth
                    <form action="{{ route('reviews.store', $book) }}" method="POST" class="border rounded-3 p-3 mb-4">
                        @csrf
                        <h6 class="mb-2">{{ $userReview ? 'Cập nhật đánh giá của bạn' : 'Viết đánh giá của bạn' }}</h6>
                        <div class="rating-input mb-2">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" {{ (int) old('rating', $userReview?->rating ?? 0) === $i ? 'checked' : '' }}>
                                <label for="star{{ $i }}" title="{{ $i }} sao"><i class="bi bi-star-fill"></i></label>
                            @endfor
                        </div>
                        @error('rating')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                        <textarea name="comment" rows="3" class="form-control mb-2 @error('comment') is-invalid @enderror" placeholder="Chia sẻ cảm nhận của bạn về cuốn sách...">{{ old('comment', $userReview?->comment ?? '') }}</textarea>
                        @error('comment')<div class="invalid-feedback d-block mb-2">{{ $message }}</div>@enderror
                        <button type="submit" class="btn btn-primary btn-sm">{{ $userReview ? 'Cập nhật đánh giá' : 'Gửi đánh giá' }}</button>
                    </form>
                    @if($userReview)
                        <form action="{{ route('reviews.destroy', $userReview) }}" method="POST" class="mb-4" onsubmit="return confirm('Xoá đánh giá của bạn?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash me-1"></i>Xoá đánh giá của tôi</button>
                        </form>
                    @endif
                @else
                    <div class="alert alert-light border mb-4">
                        <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Đăng nhập</a> để viết đánh giá cho sản phẩm này.
                    </div>
                @endauth

                {{-- Danh sách đánh giá --}}
                @forelse($book->reviews as $review)
                    <div class="border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="fw-semibold">{{ $review->user->full_name ?? $review->user->username ?? 'Người dùng' }}</span>
                                @if($review->is_verified_purchase)
                                    <span class="badge badge-soft-success ms-1"><i class="bi bi-patch-check-fill me-1"></i>Đã mua hàng</span>
                                @endif
                                <div class="mt-1">@include('books._stars', ['rating' => $review->rating])</div>
                            </div>
                            <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                        </div>
                        @if($review->comment)
                            <p class="mb-0 mt-2">{{ $review->comment }}</p>
                        @endif
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-chat-quote"></i>
                        <h5>Chưa có đánh giá</h5>
                        <p>Hãy là người đầu tiên đánh giá sản phẩm này.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Related books --}}
@if($relatedBooks->count() > 0)
<section>
    <div class="section-header">
        <h2 class="section-title">Sách liên quan</h2>
    </div>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">
        @foreach($relatedBooks as $relatedBook)
            <div class="col">
                @include('books._product-card', ['book' => $relatedBook])
            </div>
        @endforeach
    </div>
</section>
@endif

@push('styles')
<style>
    .rating-input { display: inline-flex; flex-direction: row-reverse; justify-content: flex-end; font-size: 26px; }
    .rating-input input { display: none; }
    .rating-input label { color: #d8d8d8; padding: 0 2px; cursor: pointer; transition: color .15s; }
    .rating-input label:hover, .rating-input label:hover ~ label, .rating-input input:checked ~ label { color: #ffc107; }
    .book-image-wrap {
        background: #fafafa;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        border: 1px solid var(--border-light);
    }
    .book-image-wrap .main-image {
        max-height: 450px;
        object-fit: contain;
    }
    .book-title { font-size: 24px; font-weight: 700; margin-bottom: 10px; line-height: 1.3; }
    .price-box {
        background: linear-gradient(135deg, #fff8e1, #fffde7);
        padding: 16px 20px;
        border-radius: 10px;
        margin: 16px 0;
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }
    .current-price { font-size: 32px; font-weight: 800; color: var(--primary); }

    .info-table { border-top: 1px solid var(--border-light); padding-top: 14px; }
    .info-row { display: flex; gap: 12px; padding: 6px 0; font-size: 14px; }
    .info-label { color: var(--text-muted); min-width: 120px; }
    .info-value { color: var(--text-dark); font-weight: 500; }

    .quantity-control {
        display: inline-flex;
        align-items: center;
        border: 1px solid var(--border-light);
        border-radius: 6px;
        overflow: hidden;
    }
    .btn-qty {
        width: 38px; height: 38px;
        background: #fff;
        border: none;
        color: var(--text-dark);
    }
    .btn-qty:hover { background: var(--bg-soft); color: var(--primary); }
    .quantity-control input {
        width: 60px;
        height: 38px;
        border: none;
        text-align: center;
        font-weight: 600;
        outline: none;
        border-left: 1px solid var(--border-light);
        border-right: 1px solid var(--border-light);
        -moz-appearance: textfield;
    }
    .quantity-control input::-webkit-outer-spin-button,
    .quantity-control input::-webkit-inner-spin-button { -webkit-appearance: none; }

    .benefit-list {
        background: var(--bg-soft);
        padding: 12px 16px;
        border-radius: 8px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px 16px;
    }
    .benefit-item { font-size: 13px; }
    .benefit-item i { margin-right: 6px; font-size: 16px; }

    .nav-tabs .nav-link {
        border: none;
        color: var(--text-muted);
        font-weight: 600;
        padding: 12px 20px;
        border-bottom: 2px solid transparent;
    }
    .nav-tabs .nav-link.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
        background: transparent;
    }
    .book-description { line-height: 1.8; color: #444; white-space: pre-line; }

    .breadcrumb-item + .breadcrumb-item::before { content: '›'; }
    .breadcrumb { background: transparent; padding: 0; font-size: 13px; }
</style>
@endpush

@push('scripts')
<script>
function incQty() {
    const i = document.getElementById('qty');
    if (parseInt(i.value) < parseInt(i.max)) i.value = parseInt(i.value) + 1;
}
function decQty() {
    const i = document.getElementById('qty');
    if (parseInt(i.value) > 1) i.value = parseInt(i.value) - 1;
}
</script>
@endpush
@endsection
