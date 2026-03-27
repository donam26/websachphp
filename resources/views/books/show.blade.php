@extends('layouts.app')

@section('title', $book->title)

@section('content')
<div class="fs-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav style="margin-bottom:2rem;">
            <ol class="breadcrumb" style="background:none;padding:0;margin:0;font-size:0.82rem;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:var(--fs-muted);text-decoration:none;">Trang chu</a></li>
                <li class="breadcrumb-item"><a href="{{ route('books.index') }}" style="color:var(--fs-muted);text-decoration:none;">San pham</a></li>
                @if($book->category)
                <li class="breadcrumb-item">
                    <a href="{{ route('books.category', $book->category->slug) }}" style="color:var(--fs-muted);text-decoration:none;">{{ $book->category->name }}</a>
                </li>
                @endif
                <li class="breadcrumb-item" style="color:var(--fs-accent);">{{ Str::limit($book->title, 30) }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            <!-- Product Image -->
            <div class="col-lg-6">
                <div style="position:sticky;top:100px;">
                    <div class="fs-card" style="overflow:hidden;border-radius:20px;aspect-ratio:3/4;">
                        <img src="{{ asset('storage/books/' . $book->image) }}"
                             alt="{{ $book->title }}"
                             style="width:100%;height:100%;object-fit:cover;">
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6">
                <div style="padding-top:0.5rem;">
                    <!-- Tags -->
                    <div style="display:flex;gap:8px;margin-bottom:1rem;">
                        <span class="fs-badge fs-badge-dark">
                            {{ $book->gender == 'nam' ? 'Nam' : ($book->gender == 'nu' ? 'Nu' : 'Unisex') }}
                        </span>
                        <span class="fs-badge fs-badge-accent">
                            {{ $book->category ? $book->category->name : 'Chua phan loai' }}
                        </span>
                    </div>

                    <!-- Title -->
                    <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:var(--fs-primary);margin-bottom:0.75rem;line-height:1.3;">
                        {{ $book->title }}
                    </h1>

                    <!-- Brand & Material -->
                    <div style="display:flex;flex-wrap:wrap;gap:1.5rem;margin-bottom:1.5rem;padding-bottom:1.5rem;border-bottom:1px solid var(--fs-border);">
                        <div>
                            <span style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;color:var(--fs-muted);display:block;margin-bottom:2px;">Thuong hieu</span>
                            <span style="font-weight:600;">{{ $book->brand ?: $book->author }}</span>
                        </div>
                        @if($book->material)
                        <div>
                            <span style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;color:var(--fs-muted);display:block;margin-bottom:2px;">Chat lieu</span>
                            <span style="font-weight:600;">{{ $book->material }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Price -->
                    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:2rem;">
                        <span style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:var(--fs-primary);">
                            {{ number_format($book->price) }}d
                        </span>
                        @if($book->quantity > 0)
                            <span class="fs-badge fs-badge-success" style="font-size:0.75rem;">Con hang ({{ $book->quantity }})</span>
                        @else
                            <span class="fs-badge fs-badge-danger" style="font-size:0.75rem;">Het hang</span>
                        @endif
                    </div>

                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}">

                        <!-- Size Selection -->
                        @if($book->sizes)
                        <div style="margin-bottom:1.5rem;">
                            <label style="font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:var(--fs-muted);display:block;margin-bottom:0.75rem;">
                                Kich co
                            </label>
                            <div style="display:flex;flex-wrap:wrap;gap:8px;">
                                @foreach(explode(',', $book->sizes) as $index => $size)
                                <div>
                                    <input type="radio" class="btn-check" name="size"
                                           id="size-{{ $index }}" value="{{ trim($size) }}"
                                           {{ $index == 0 ? 'checked' : '' }}>
                                    <label class="size-btn" for="size-{{ $index }}">{{ trim($size) }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Color Selection -->
                        @if($book->colors)
                        <div style="margin-bottom:1.5rem;">
                            <label style="font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:var(--fs-muted);display:block;margin-bottom:0.75rem;">
                                Mau sac
                            </label>
                            <div style="display:flex;flex-wrap:wrap;gap:8px;">
                                @foreach(explode(',', $book->colors) as $index => $color)
                                <div>
                                    <input type="radio" class="btn-check" name="color"
                                           id="color-{{ $index }}" value="{{ trim($color) }}"
                                           {{ $index == 0 ? 'checked' : '' }}>
                                    <label class="size-btn" for="color-{{ $index }}">{{ trim($color) }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Quantity & Add to Cart -->
                        <div style="display:flex;gap:12px;align-items:stretch;margin-bottom:2rem;">
                            <div class="qty-control">
                                <button class="qty-btn" type="button" onclick="decrementQuantity()">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" class="qty-input" name="quantity"
                                       value="1" min="1" max="{{ $book->quantity }}" id="quantity">
                                <button class="qty-btn" type="button" onclick="incrementQuantity()">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                            <button type="submit" class="fs-btn fs-btn-primary" style="flex:1;font-size:0.85rem;"
                                    {{ $book->quantity == 0 ? 'disabled' : '' }}>
                                <i class="bi bi-bag-plus"></i> Them vao gio hang
                            </button>
                        </div>
                    </form>

                    <!-- Description -->
                    <div style="padding-top:1.5rem;border-top:1px solid var(--fs-border);">
                        <h6 style="font-family:'Playfair Display',serif;font-weight:600;margin-bottom:0.75rem;">Mo ta san pham</h6>
                        <div style="color:var(--fs-muted);line-height:1.8;font-size:0.9rem;">
                            {{ $book->description }}
                        </div>
                    </div>

                    <!-- Share -->
                    <div style="margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid var(--fs-border);display:flex;align-items:center;gap:12px;">
                        <span style="font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:var(--fs-muted);">Chia se</span>
                        <a href="#" class="share-btn"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="share-btn"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="share-btn"><i class="bi bi-pinterest"></i></a>
                        <a href="#" class="share-btn"><i class="bi bi-link-45deg"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedBooks->count() > 0)
        <div style="margin-top:5rem;">
            <div style="text-align:center;margin-bottom:2.5rem;">
                <h3 class="fs-section-title">San pham lien quan</h3>
                <p style="color:var(--fs-muted);font-size:0.875rem;">Ban cung co the thich nhung san pham nay</p>
            </div>
            <div class="row g-4">
                @foreach($relatedBooks as $relatedBook)
                <div class="col-6 col-md-3">
                    <div class="fs-card related-card" style="height:100%;">
                        <a href="{{ route('books.show', $relatedBook) }}" style="display:block;overflow:hidden;aspect-ratio:3/4;">
                            <img src="{{ asset('storage/books/' . $relatedBook->image) }}"
                                 alt="{{ $relatedBook->title }}" loading="lazy"
                                 style="width:100%;height:100%;object-fit:cover;transition:transform 0.6s ease;">
                        </a>
                        <div style="padding:1rem;">
                            <h6 style="font-size:0.85rem;font-weight:600;margin-bottom:0.25rem;">
                                <a href="{{ route('books.show', $relatedBook) }}" style="color:var(--fs-text);text-decoration:none;">
                                    {{ Str::limit($relatedBook->title, 35) }}
                                </a>
                            </h6>
                            <p style="color:var(--fs-muted);font-size:0.75rem;margin-bottom:0.5rem;">
                                {{ $relatedBook->brand ?: $relatedBook->author }}
                            </p>
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span style="font-family:'Playfair Display',serif;font-weight:700;color:var(--fs-primary);">{{ number_format($relatedBook->price) }}d</span>
                                @if($relatedBook->quantity > 0)
                                    <span class="fs-badge fs-badge-success" style="font-size:0.6rem;">Con hang</span>
                                @else
                                    <span class="fs-badge fs-badge-danger" style="font-size:0.6rem;">Het hang</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function incrementQuantity() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.getAttribute('max'));
    if (parseInt(input.value) < max) input.value = parseInt(input.value) + 1;
}
function decrementQuantity() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
}
</script>
@endpush

@push('styles')
<style>
.size-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 48px;
    height: 42px;
    padding: 0 14px;
    border: 1.5px solid var(--fs-border);
    border-radius: 10px;
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--fs-text);
    cursor: pointer;
    transition: var(--fs-transition);
    background: #fff;
}

.size-btn:hover {
    border-color: var(--fs-primary);
}

.btn-check:checked + .size-btn {
    background: var(--fs-primary);
    border-color: var(--fs-primary);
    color: #fff;
}

.qty-control {
    display: flex;
    align-items: center;
    border: 1.5px solid var(--fs-border);
    border-radius: 100px;
    overflow: hidden;
    background: #fff;
}

.qty-btn {
    width: 44px;
    height: 44px;
    border: none;
    background: transparent;
    font-size: 1.1rem;
    color: var(--fs-text);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--fs-transition);
}

.qty-btn:hover { background: var(--fs-surface); }

.qty-input {
    width: 48px;
    text-align: center;
    border: none;
    font-weight: 600;
    font-size: 0.9rem;
    -moz-appearance: textfield;
    background: transparent;
}

.qty-input::-webkit-inner-spin-button,
.qty-input::-webkit-outer-spin-button { -webkit-appearance: none; }

.share-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 1px solid var(--fs-border);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: var(--fs-muted);
    text-decoration: none;
    transition: var(--fs-transition);
}

.share-btn:hover {
    border-color: var(--fs-accent);
    color: var(--fs-accent);
    transform: translateY(-2px);
}

.related-card:hover img { transform: scale(1.08); }

.breadcrumb-item + .breadcrumb-item::before { content: "/"; color: var(--fs-border); }
</style>
@endpush
@endsection
