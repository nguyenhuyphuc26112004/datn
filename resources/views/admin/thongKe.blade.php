@extends('admin.layouts.master')

@section('title', 'Thống kê doanh thu')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title-group">
            <h1><i class="fa-solid fa-chart-pie"></i> Báo cáo & Thống kê doanh thu</h1>
            <p>Biểu đồ doanh thu 12 tháng, cơ cấu sản phẩm bán chạy và danh sách đơn hàng thành công.</p>
        </div>
    </div>

    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="alert-box alert-box-success">
            <i class="fa-solid fa-circle-check" style="font-size: 18px;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-box alert-box-error">
            <i class="fa-solid fa-triangle-exclamation" style="font-size: 18px;"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filter Date Form -->
    <div class="card-container" style="margin-bottom: 24px;">
        <div style="padding: 18px 24px; background: #ffffff;">
            <form id="filterForm" method="GET" action="{{ route('admin.thongKe') }}" style="display: flex; align-items: flex-end; gap: 16px; flex-wrap: wrap;">
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-size: 13px; font-weight: 600; color: #475569;">Từ ngày</label>
                    <input type="date" id="startDateInput" name="start_date" value="{{ $startDate }}" 
                           style="padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none; font-family: inherit;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="font-size: 13px; font-weight: 600; color: #475569;">Đến ngày</label>
                    <input type="date" id="endDateInput" name="end_date" value="{{ $endDate }}" 
                           style="padding: 9px 12px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none; font-family: inherit;">
                </div>
                <button type="submit" class="btn-search-action">
                    <i class="fa-solid fa-filter"></i> Lọc thống kê
                </button>
            </form>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #eff6ff; color: #2563eb;">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Tổng doanh thu toàn thời gian</span>
                <span class="stat-value" style="font-size: 20px; color: #2563eb;">
                    {{ number_format($totalAllTime, 0, ',', '.') }} đ
                </span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #ecfdf5; color: #10b981;">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Doanh thu trong kỳ lọc</span>
                <span class="stat-value" style="font-size: 20px; color: #059669;">
                    {{ number_format($totalFiltered, 0, ',', '.') }} đ
                </span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #fffbeb; color: #d97706;">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Số đơn hoàn tất</span>
                <span class="stat-value">{{ $totalRows }}</span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div style="display: grid; grid-template-columns: 1.6fr 1fr; gap: 20px; margin-bottom: 28px;">
        <div class="card-container" style="margin-bottom: 0;">
            <div class="card-header-tools" style="background: #f8fafc; border-bottom: 1px solid var(--border-color);">
                <strong style="font-size: 15px; color: #0f172a;"><i class="fa-solid fa-chart-area" style="color: #2563eb;"></i> Doanh thu theo 12 tháng</strong>
            </div>
            <div style="padding: 20px;">
                <canvas id="revChart" style="max-height: 280px;"></canvas>
            </div>
        </div>

        <div class="card-container" style="margin-bottom: 0;">
            <div class="card-header-tools" style="background: #f8fafc; border-bottom: 1px solid var(--border-color);">
                <strong style="font-size: 15px; color: #0f172a;"><i class="fa-solid fa-chart-pie" style="color: #10b981;"></i> Top 5 sản phẩm bán chạy</strong>
            </div>
            <div style="padding: 20px; display: flex; justify-content: center;">
                <canvas id="pieChart" style="max-height: 280px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card-container">
        <div class="card-header-tools" style="background: #f8fafc; border-bottom: 1px solid var(--border-color);">
            <strong style="font-size: 15px; color: #0f172a;">Danh sách đơn thành công trong kỳ lọc</strong>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Mã đơn</th>
                        <th>Ngày đặt</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th style="text-align: center;">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $row)
                        <tr>
                            <td><span class="product-id-badge">#{{ $row->id }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($row->ngay_dat)->format('d/m/Y') }}</td>
                            <td><strong style="color: #0f172a;">{{ $row->ten }}</strong></td>
                            <td>
                                <strong style="color: #059669; font-size: 15px;">
                                    {{ number_format($row->tong_tien, 0, ',', '.') }} đ
                                </strong>
                            </td>
                            <td style="text-align: center;">
                                <span class="role-badge" style="background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;">
                                    <i class="fa-solid fa-check-double"></i> Đã thanh toán
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fa-solid fa-receipt"></i>
                                    </div>
                                    <h3>Không có đơn hàng nào trong khoảng thời gian này</h3>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($orders, 'hasPages') && $orders->hasPages())
            <div class="card-footer-pagination">
                {{ $orders->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Doanh thu 12 tháng
    new Chart(document.getElementById('revChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [{ 
                label: 'Doanh thu tháng (VNĐ)', 
                data: {!! json_encode($revenues) !!}, 
                borderColor: '#2563eb', 
                backgroundColor: 'rgba(37, 99, 235, 0.1)', 
                borderWidth: 2,
                tension: 0.3,
                fill: true 
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Top 5 sản phẩm bán chạy
    new Chart(document.getElementById('pieChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($spNames) !!},
            datasets: [{ 
                data: {!! json_encode($spQtys) !!}, 
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ec4899', '#8b5cf6'] 
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Kiểm tra ngày bắt đầu không lớn hơn ngày kết thúc
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            const start = document.getElementById('startDateInput').value;
            const end = document.getElementById('endDateInput').value;
            if (start && end && start > end) {
                e.preventDefault();
                alert('Ngày bắt đầu không được lớn hơn ngày kết thúc!');
            }
        });
    }
</script>
@endpush