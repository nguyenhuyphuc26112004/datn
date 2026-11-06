@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title-group">
            <h1><i class="fa-solid fa-gauge-high"></i> Dashboard Tổng quan</h1>
            <p>Tổng quan chỉ số hoạt động kinh doanh và hiệu suất toàn hệ thống.</p>
        </div>
    </div>
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

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #eff6ff; color: #2563eb;">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Người dùng</span>
                <span class="stat-value">{{ number_format($totalUsers) }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #ecfdf5; color: #10b981;">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Tổng đơn hàng</span>
                <span class="stat-value">{{ number_format($totalOrders) }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #fffbeb; color: #d97706;">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Tổng sản phẩm</span>
                <span class="stat-value">{{ number_format($totalProducts) }}</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #fef2f2; color: #dc2626;">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Đơn chờ duyệt</span>
                <span class="stat-value">{{ number_format($totalPending) }}</span>
            </div>
        </div>
    </div>

    <!-- Recent Orders Section -->
    <div class="card-container">
        <div class="card-header-tools" style="background: #f8fafc; border-bottom: 1px solid var(--border-color);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-clock-rotate-left" style="color: #2563eb; font-size: 18px;"></i>
                <strong style="font-size: 16px; color: #0f172a;">Đơn hàng mới nhất</strong>
            </div>
            <a href="{{ route('admin.donhang.index') }}" class="btn-action btn-action-view">
                Xem tất cả đơn hàng <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Thanh toán</th>
                        <th style="text-align: center;">Trạng thái</th>
                        <th style="text-align: right;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentOrders as $dH)
                        <tr>
                            <td><span class="product-id-badge">#{{ $dH->id }}</span></td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 2px;">
                                    <strong style="color: #0f172a;">{{ $dH->ten }}</strong>
                                    <span style="font-size: 12px; color: #64748b;">{{ $dH->sdt }}</span>
                                </div>
                            </td>
                            <td>
                                <strong style="color: #059669; font-size: 15px;">
                                    {{ number_format($dH->tong_tien, 0, ',', '.') }} đ
                                </strong>
                            </td>
                            <td>
                                <span style="font-size: 13px; color: #475569; background: #f1f5f9; padding: 3px 8px; border-radius: 6px;">
                                    {{ $dH->pt_thanh_toan ?? 'COD' }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                @if($dH->trang_thai == 'Đang chờ duyệt')
                                    <span class="role-badge" style="background: #fffbeb; color: #b45309; border: 1px solid #fde68a;">
                                        <i class="fa-solid fa-clock"></i> Chờ duyệt
                                    </span>
                                @elseif($dH->trang_thai == 'Đang vận chuyển')
                                    <span class="role-badge" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;">
                                        <i class="fa-solid fa-truck-fast"></i> Đang giao
                                    </span>
                                @elseif($dH->trang_thai == 'Đã nhận hàng')
                                    <span class="role-badge" style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;">
                                        <i class="fa-solid fa-circle-check"></i> Hoàn tất
                                    </span>
                                @elseif($dH->trang_thai == 'Đã hủy')
                                    <span class="role-badge" style="background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca;">
                                        <i class="fa-solid fa-ban"></i> Đã hủy
                                    </span>
                                @else
                                    <span class="role-badge" style="background: #f1f5f9; color: #475569;">
                                        {{ $dH->trang_thai }}
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <a class="btn-action btn-action-view" href="{{ route('admin.donhang.show', $dH->id) }}" title="Xem chi tiết">
                                    <i class="fa-solid fa-eye"></i> Xem
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fa-solid fa-receipt"></i>
                                    </div>
                                    <h3>Chưa có đơn hàng nào trong hệ thống</h3>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection