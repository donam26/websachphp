@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-6 col-xl-3">
        <a href="{{ route('admin.orders.index', ['from' => $startOfMonth->toDateString(), 'to' => $endOfMonth->toDateString()]) }}" class="stat-card-link" title="Xem đơn hàng trong tháng này">
            <div class="stat-card primary">
                <i class="bi bi-currency-dollar stat-icon"></i>
                <div class="stat-label">Doanh thu tháng {{ $startOfMonth->format('n') }}</div>
                <div class="stat-value">{{ number_format($monthRevenue ?? 0, 0, ',', '.') }}đ</div>
                <div class="stat-foot"><i class="bi bi-graph-up"></i> Tổng all-time: {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}đ</div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-xl-3">
        <a href="{{ route('admin.orders.index', ['status' => \App\Models\Order::STATUS_PENDING]) }}" class="stat-card-link" title="Xem các đơn đang chờ xử lý">
            <div class="stat-card warning">
                <i class="bi bi-receipt stat-icon"></i>
                <div class="stat-label">Đơn chờ xử lý</div>
                <div class="stat-value">{{ $pendingOrders ?? 0 }}</div>
                <div class="stat-foot">Hôm nay có {{ $todayOrders ?? 0 }} đơn mới <i class="bi bi-arrow-right-short"></i></div>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-xl-3">
        <a href="{{ route('admin.books.index', ['stock' => 'low']) }}" class="stat-card-link" title="Xem sách sắp hết hàng">
            <div class="stat-card success">
                <i class="bi bi-box-seam stat-icon"></i>
                <div class="stat-label">Sách sắp hết</div>
                <div class="stat-value">{{ $lowStockCount ?? 0 }}</div>
                <div class="stat-foot">{{ $outOfStockBooks ?? 0 }} sách đã hết hàng</div>
            </div>
        </a>
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
    <div class="col-12">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span><i class="bi bi-bar-chart me-2 text-primary"></i>Doanh thu theo ngày</span>
                <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 flex-wrap">
                    <input type="hidden" name="year" value="{{ $year }}">
                    <input type="hidden" name="top_period" value="{{ $topPeriod }}">
                    <input type="date" name="rev_from" class="form-control form-control-sm" style="width:148px" value="{{ $revFrom->toDateString() }}" max="{{ $revTo->toDateString() }}">
                    <span class="text-muted small">→</span>
                    <input type="date" name="rev_to" class="form-control form-control-sm" style="width:148px" value="{{ $revTo->toDateString() }}">
                    <button type="submit" class="btn btn-sm btn-primary" title="Xem khoảng đã chọn"><i class="bi bi-funnel"></i></button>
                    <a href="{{ route('admin.dashboard', ['year' => $year, 'top_period' => $topPeriod]) }}" class="btn btn-sm btn-outline-secondary" title="14 ngày gần nhất"><i class="bi bi-arrow-counterclockwise"></i></a>
                </form>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span><i class="bi bi-calendar3 me-2 text-primary"></i>Doanh thu 12 tháng năm {{ $year }}</span>
                <form method="GET" action="{{ route('admin.dashboard') }}">
                    <input type="hidden" name="rev_from" value="{{ $revFrom->toDateString() }}">
                    <input type="hidden" name="rev_to" value="{{ $revTo->toDateString() }}">
                    <input type="hidden" name="top_period" value="{{ $topPeriod }}">
                    <select name="year" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
                        @foreach($yearOptions as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Năm {{ $y }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="card-body">
                <canvas id="monthlyRevenueChart" height="80"></canvas>
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
            <div class="card-header d-flex justify-content-between align-items-center gap-2">
                <span class="text-nowrap"><i class="bi bi-fire me-2 text-danger"></i>Top bán chạy</span>
                <form method="GET" action="{{ route('admin.dashboard') }}" class="ms-auto">
                    <input type="hidden" name="rev_from" value="{{ $revFrom->toDateString() }}">
                    <input type="hidden" name="rev_to" value="{{ $revTo->toDateString() }}">
                    <input type="hidden" name="year" value="{{ $year }}">
                    <select name="top_period" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()" title="Khoảng thời gian">
                        <option value="week" {{ $topPeriod === 'week' ? 'selected' : '' }}>7 ngày qua</option>
                        <option value="month" {{ $topPeriod === 'month' ? 'selected' : '' }}>30 ngày qua</option>
                        <option value="all" {{ $topPeriod === 'all' ? 'selected' : '' }}>Tất cả</option>
                    </select>
                </form>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($topBooks ?? [] as $i => $book)
                        <div class="list-group-item d-flex align-items-center gap-3">
                            <div class="top-rank rank-{{ $i + 1 }}">{{ $i + 1 }}</div>
                            <img src="{{ $book->image_url }}"
                                 style="width:40px;height:54px;object-fit:cover;border-radius:4px;"
                                 onerror="this.src='https://placehold.co/50x70/f4f6f8/4f46e5?text=Book'">
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

@push('styles')
<style>
.stat-card-link { display: block; text-decoration: none; color: inherit; }
.stat-card-link .stat-card { transition: transform .2s, box-shadow .2s; }
.stat-card-link:hover .stat-card { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,.12); }
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
    const monthlyData = @json($revenueByMonth);

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: revenueData.labels,
            datasets: [{
                label: 'Doanh thu (VND)',
                data: revenueData.values,
                fill: true,
                // monotone: đường nội suy không "võng" xuống dưới 0 giữa các điểm.
                cubicInterpolationMode: 'monotone',
                tension: .35,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79,70,229,.08)',
                pointBackgroundColor: '#4f46e5',
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                // Khoá trục Y bắt đầu từ 0 -> không hiển thị phần âm.
                y: {
                    beginAtZero: true,
                    min: 0,
                    ticks: { callback: v => new Intl.NumberFormat('vi-VN').format(v) }
                }
            }
        }
    });

    new Chart(document.getElementById('monthlyRevenueChart'), {
        type: 'bar',
        data: {
            labels: monthlyData.labels,
            datasets: [{
                label: 'Doanh thu (VND)',
                data: monthlyData.values,
                backgroundColor: 'rgba(16,185,129,.75)',
                borderRadius: 4,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    min: 0,
                    ticks: { callback: v => new Intl.NumberFormat('vi-VN').format(v) }
                }
            }
        }
    });
})();
</script>
@endpush
@endsection
