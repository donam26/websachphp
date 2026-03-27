@extends('layouts.admin')

@section('title', 'Quan ly san pham')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
    <h1 style="font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:var(--ad-primary);margin:0;">
        Quan ly san pham
    </h1>
    <a href="{{ route('admin.books.create') }}" class="ad-btn ad-btn-primary">
        <i class="bi bi-plus-lg"></i> Them san pham
    </a>
</div>

<div class="ad-card">
    <!-- Filters -->
    <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--ad-border);">
        <form action="{{ route('admin.books.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Tim theo ten SP hoac thuong hieu" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="category_id" class="form-select">
                        <option value="">Tat ca danh muc</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="gender" class="form-select">
                        <option value="">Tat ca gioi tinh</option>
                        <option value="nam" {{ request('gender') == 'nam' ? 'selected' : '' }}>Nam</option>
                        <option value="nu" {{ request('gender') == 'nu' ? 'selected' : '' }}>Nu</option>
                        <option value="unisex" {{ request('gender') == 'unisex' ? 'selected' : '' }}>Unisex</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="ad-btn ad-btn-primary w-100">
                        <i class="bi bi-search"></i> Tim
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div style="overflow-x:auto;">
        <table class="ad-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>San pham</th>
                    <th>Thuong hieu</th>
                    <th>Danh muc</th>
                    <th>Gioi tinh</th>
                    <th>Size</th>
                    <th>Gia</th>
                    <th>Kho</th>
                    <th>Trang thai</th>
                    <th>Thao tac</th>
                </tr>
            </thead>
            <tbody>
                @foreach($books as $book)
                <tr>
                    <td style="color:var(--ad-muted);">#{{ $book->id }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <img src="{{ asset('storage/books/'.$book->image) }}"
                                 alt="{{ $book->title }}"
                                 style="width:44px;height:56px;object-fit:cover;border-radius:8px;border:1px solid var(--ad-border);">
                            <span style="font-weight:500;">{{ Str::limit($book->title, 28) }}</span>
                        </div>
                    </td>
                    <td style="font-size:0.85rem;">{{ $book->brand ?: $book->author }}</td>
                    <td>
                        <span class="ad-badge" style="background:var(--ad-accent-light);color:#8b6914;">
                            {{ $book->category ? $book->category->name : '-' }}
                        </span>
                    </td>
                    <td>
                        <span class="ad-badge {{ $book->gender == 'nam' ? 'ad-badge-info' : ($book->gender == 'nu' ? 'ad-badge-danger' : 'ad-badge-dark') }}">
                            {{ $book->gender == 'nam' ? 'Nam' : ($book->gender == 'nu' ? 'Nu' : 'Unisex') }}
                        </span>
                    </td>
                    <td>
                        @if($book->sizes)
                            <div style="display:flex;flex-wrap:wrap;gap:3px;">
                                @foreach(explode(',', $book->sizes) as $size)
                                    <span style="padding:2px 6px;background:var(--ad-surface);border-radius:4px;font-size:0.7rem;font-weight:500;">{{ trim($size) }}</span>
                                @endforeach
                            </div>
                        @else - @endif
                    </td>
                    <td style="font-weight:600;white-space:nowrap;">{{ number_format($book->price) }}d</td>
                    <td>
                        <span style="font-weight:600;{{ $book->quantity < 10 ? 'color:var(--ad-danger);' : '' }}">
                            {{ $book->quantity }}
                        </span>
                    </td>
                    <td>
                        @if($book->status === 'available')
                            <span class="ad-badge ad-badge-success">Con hang</span>
                        @else
                            <span class="ad-badge ad-badge-danger">Het hang</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.books.edit', $book) }}" class="ad-btn ad-btn-outline ad-btn-icon ad-btn-sm" title="Chinh sua">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ad-btn ad-btn-danger ad-btn-icon ad-btn-sm"
                                        onclick="return confirm('Ban co chac muon xoa san pham nay?')" title="Xoa">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($books->hasPages())
    <div style="padding:1rem 1.5rem;border-top:1px solid var(--ad-border);display:flex;justify-content:center;">
        {{ $books->links() }}
    </div>
    @endif
</div>
@endsection
