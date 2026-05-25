@extends('layouts.admin')

@section('title', 'Quản lý sách')

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-1"><i class="bi bi-book me-2 text-primary"></i>Quản lý sách</h5>
                <small class="text-muted">Tổng cộng {{ $books->total() }} cuốn sách</small>
            </div>
            <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Thêm sách mới
            </a>
        </div>

        <form action="{{ route('admin.books.index') }}" method="GET" class="mt-3">
            <div class="row g-2">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Tìm theo tên sách hoặc tác giả..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="stock" class="form-select">
                        <option value="">Tồn kho</option>
                        <option value="in" {{ request('stock') === 'in' ? 'selected' : '' }}>Còn nhiều</option>
                        <option value="low" {{ request('stock') === 'low' ? 'selected' : '' }}>Sắp hết</option>
                        <option value="out" {{ request('stock') === 'out' ? 'selected' : '' }}>Hết hàng</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary flex-grow-1"><i class="bi bi-funnel"></i></button>
                    @if(request()->hasAny(['search', 'category_id', 'stock']))
                        <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-3" width="60">ID</th>
                        <th width="70">Ảnh</th>
                        <th>Tên sách</th>
                        <th>Danh mục</th>
                        <th class="text-end">Giá</th>
                        <th class="text-center">Kho</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-end pe-3" width="130">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                        <tr>
                            <td class="ps-3 text-muted">#{{ $book->id }}</td>
                            <td>
                                <img src="{{ $book->image_url }}"
                                     onerror="this.src='https://placehold.co/60x80/f4f6f8/c92127?text=Book'"
                                     style="width:48px;height:64px;object-fit:cover;border-radius:6px;">
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $book->title }}</div>
                                <small class="text-muted"><i class="bi bi-person me-1"></i>{{ $book->author }}</small>
                            </td>
                            <td>
                                @if($book->category)
                                    <span class="badge badge-soft-primary">{{ $book->category->name }}</span>
                                @else
                                    <span class="text-muted small">Chưa phân loại</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="fw-semibold text-primary">{{ number_format($book->price, 0, ',', '.') }}đ</div>
                                @if($book->compare_price && $book->compare_price > $book->price)
                                    <small class="text-muted text-decoration-line-through">{{ number_format($book->compare_price, 0, ',', '.') }}đ</small>
                                @endif
                            </td>
                            <td class="text-center">
                                @php $qcls = $book->quantity === 0 ? 'text-danger' : ($book->quantity < 5 ? 'text-warning' : 'text-success'); @endphp
                                <span class="fw-semibold {{ $qcls }}">{{ $book->quantity }}</span>
                            </td>
                            <td class="text-center">
                                @if($book->is_available)
                                    <span class="badge badge-soft-success">Đang bán</span>
                                @elseif($book->quantity === 0)
                                    <span class="badge badge-soft-danger">Hết hàng</span>
                                @else
                                    <span class="badge badge-soft-secondary">Ngừng bán</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('books.show', $book) }}" target="_blank" class="btn btn-outline-secondary" title="Xem"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-outline-primary" title="Sửa"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="d-inline" onsubmit="return confirm('Xoá sách này?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Xoá"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">Không tìm thấy sách nào</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($books->hasPages())
        <div class="card-footer bg-white">{{ $books->links() }}</div>
    @endif
</div>
@endsection
