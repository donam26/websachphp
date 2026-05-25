@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="stat-card primary">
            <i class="bi bi-currency-dollar stat-icon"></i>
            <div class="stat-label">Doanh thu tháng</div>
            <div class="stat-value">{{ number_format($monthRevenue ?? 0, 0, ',', '.') }}đ</div>
            <div class="stat-foot"><i class="bi bi-graph-up"></i> Tổng all-time: {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}đ</div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card warning">
            <i class="bi bi-receipt stat-icon"></i>
            <div class="stat-label">Đơn chờ xử lý</div>
            <div class="stat-value">{{ $pendingOrders ?? 0 }}</div>
            <div class="stat-foot">Hôm nay có {{ $todayOrders ?? 0 }} đơn mới</div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card success">
            <i class="bi bi-book stat-icon"></i>
            <div class="stat-label">Tổng số sách</div>
            <div class="stat-value">{{ $totalBooks ?? 0 }}</div>
            <div class="stat-foot">{{ $outOfStockBooks ?? 0 }} sách đã hết hàng</div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card info">
            <i class="bi bi-people stat-icon"></i>
            <div class="stat-label">Khách hàng</div>
            <div class="stat-value">{{ $totalCustomers ?? 0 }}</div>
            <div class="stat-foot">Đã đăng ký</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-bar-chart me-2 text-primary"></i>Doanh thu 14 ngày gần nhất</div>
            <div class="card-body">
                <canvas id="revenueChart" height="110"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-pie-chart me-2 text-primary"></i>Đơn theo trạng thái</div>
            <div class="card-body">
                <canvas id="statusChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-receipt me-2 text-primary"></i>Đơn hàng gần đây</span>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th class="text-end pe-3">Ngày</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders ?? [] as $order)
                                <tr>
                                    <td class="ps-3">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-primary fw-semibold text-decoration-none">{{ $order->code }}</a>
                                    </td>
                                    <td>
                                        <div>{{ $order->user->full_name ?? $order->user->username }}</div>
                                        <small class="text-muted">{{ $order->user->email }}</small>
                                    </td>
                                    <td><strong>{{ number_format($order->total_amount, 0, ',', '.') }}đ</strong></td>
                                    <td><span class="badge badge-soft-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                                    <td class="text-end pe-3 small text-muted">{{ $order->created_at->format('d/m H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">Chưa có đơn hàng nào</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-fire me-2 text-danger"></i>Top sách bán chạy</span>
                <a href="{{ route('admin.books.index') }}" class="btn btn-sm btn-outline-primary">Quản lý sách</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($topBooks ?? [] as $i => $book)
                        <div class="list-group-item d-flex align-items-center gap-3">
                            <div class="top-rank rank-{{ $i + 1 }}">{{ $i + 1 }}</div>
                            <img src="{{ $book->image_url }}"
                                 style="width:40px;height:54px;object-fit:cover;border-radius:4px;"
                                 onerror="this.src='https://placehold.co/50x70/f4f6f8/c92127?text=Book'">
                            <div class="flex-fill">
                                <div class="fw-semibold small">{{ Str::limit($book->title, 38) }}</div>
                                <small class="text-muted">Đã bán: {{ $book->total_sold ?? 0 }}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-primary small">{{ number_format($book->price, 0, ',', '.') }}đ</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">Chưa có dữ liệu</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="bi bi-exclamation-triangle me-2 text-warning"></i>Sách sắp hết hàng</div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($lowStockBooks ?? [] as $book)
                        <a href="{{ route('admin.books.edit', $book) }}" class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                            <i class="bi bi-box-seam text-warning"></i>
                            <div class="flex-fill small">{{ Str::limit($book->title, 36) }}</div>
                            <span class="badge badge-soft-danger">Còn {{ $book->quantity }}</span>
                        </a>
                    @empty
                        <div class="text-center text-muted py-3 small">Tồn kho khoẻ mạnh</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-lightning me-2 text-warning"></i>Truy cập nhanh</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3 col-6"><a href="{{ route('admin.books.create') }}" class="quick-action"><i class="bi bi-plus-circle text-primary"></i><span>Thêm sách mới</span></a></div>
            <div class="col-md-3 col-6"><a href="{{ route('admin.orders.index') }}" class="quick-action"><i class="bi bi-receipt text-info"></i><span>Đơn hàng</span></a></div>
            <div class="col-md-3 col-6"><a href="{{ route('admin.categories.index') }}" class="quick-action"><i class="bi bi-tags text-success"></i><span>Danh mục</span></a></div>
            <div class="col-md-3 col-6"><a href="{{ route('admin.discounts.index') }}" class="quick-action"><i class="bi bi-ticket-perforated text-warning"></i><span>Mã giảm giá</span></a></div>
        </div>
    </div>
</div>

@push('styles')
<style>
.top-rank { width: 28px; height: 28px; border-radius: 50%; background: #e5e7eb; color: #6b7280; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; }
.top-rank.rank-1 { background: #fbbf24; color: #fff; }
.top-rank.rank-2 { background: #94a3b8; color: #fff; }
.top-rank.rank-3 { background: #b45309; color: #fff; }
.quick-action { display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 22px 10px; background: #f9fafb; border-radius: 10px; transition: .2s; color: var(--text-dark); text-decoration: none; text-align: center; }
.quick-action:hover { background: #fff; transform: translateY(-3px); box-shadow: 0 4px 12px rgba(0,0,0,.08); }
.quick-action i { font-size: 32px; }
.quick-action span { font-size: 13px; font-weight: 500; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function() {
    const revenueData = @json($revenueByDay);
    const statusData = @json($statusDistribution);
    const statusLabels = @json(\App\Models\Order::statusOptions());

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: revenueData.labels,
            datasets: [{
                label: 'Doanh thu (VND)',
                data: revenueData.values,
                fill: true,
                tension: .35,
                borderColor: '#c92127',
                backgroundColor: 'rgba(201,33,39,.08)',
                pointBackgroundColor: '#c92127',
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { ticks: { callback: v => new Intl.NumberFormat('vi-VN').format(v) } }
            }
        }
    });

    const statusKeys = Object.keys(statusData);
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: statusKeys.map(k => statusLabels[k] || k),
            datasets: [{
                data: statusKeys.map(k => statusData[k]),
                backgroundColor: ['#f59e0b', '#3b82f6', '#c92127', '#10b981', '#ef4444'],
            }]
        },
        options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } } }
    });
})();
</script>
@endpush
@endsection
