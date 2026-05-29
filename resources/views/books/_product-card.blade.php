{{-- Reusable book product card. Expects: $book --}}
<div class="product-card">
    <a href="{{ route('books.show', $book) }}" class="product-thumb">
        <img src="{{ $book->image_url }}"
             alt="{{ $book->title }}"
             onerror="this.src='https://placehold.co/300x400/eef2ff/4f46e5?text=Book'">
        @if($book->discount_percent > 0)
            <span class="product-discount">-{{ $book->discount_percent }}%</span>
        @endif
    </a>
    <div class="product-body">
        <a href="{{ route('books.show', $book) }}" class="product-title">{{ $book->title }}</a>
        @if($book->author_names)
            <div class="product-author">{{ $book->author_names }}</div>
        @endif
        @if(isset($book->reviews_count) && $book->reviews_count > 0)
            <div class="small mb-1" style="font-size:12px;">
                @include('books._stars', ['rating' => $book->reviews_avg_rating])
                <span class="text-muted">({{ $book->reviews_count }})</span>
            </div>
        @endif
        <div class="d-flex align-items-baseline gap-2">
            <span class="product-price">{{ number_format($book->price, 0, ',', '.') }}đ</span>
            @if($book->compare_price && $book->compare_price > $book->price)
                <span class="product-old-price">{{ number_format($book->compare_price, 0, ',', '.') }}đ</span>
            @endif
        </div>
        <div class="d-flex align-items-center gap-2 mt-1">
            @if($book->is_available)
                <span class="badge-soft-success">Còn hàng</span>
            @else
                <span class="badge-soft-danger">Hết hàng</span>
            @endif
            @if($book->category)
                <span class="text-muted" style="font-size:11px;">{{ $book->category->name }}</span>
            @endif
        </div>
        <div class="product-actions">
            @auth
                @if($book->is_available)
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn-add-cart">
                            <i class="bi bi-cart-plus me-1"></i> Thêm vào giỏ
                        </button>
                    </form>
                @else
                    <button class="btn-add-cart" disabled><i class="bi bi-x-circle me-1"></i>Hết hàng</button>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-add-cart d-block text-center text-decoration-none">
                    <i class="bi bi-box-arrow-in-right me-1"></i>Đăng nhập để mua
                </a>
            @endauth
        </div>
    </div>
</div>
