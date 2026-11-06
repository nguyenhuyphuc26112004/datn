@extends('admin.layouts.master')

@section('title', 'Quản lý khuyến mãi')

@section('content')
    <div class="page-header">
        <div class="page-title-group">
            <h1><i class="fa-solid fa-ticket-simple"></i> Quản lý Khuyến mãi</h1>
            <p>Tạo và quản lý mã giảm giá, voucher áp dụng cho đơn hàng của khách.</p>
        </div>
        <a href="{{ route('admin.khuyenmai.create') }}" class="btn-cta-primary">
            <i class="fa-solid fa-plus"></i> Thêm mã mới
        </a>
    </div>

    @if(session('success'))
        <div class="alert-box alert-box-success">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="alert-box alert-box-error">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #eff6ff; color: #2563eb;">
                <i class="fa-solid fa-ticket-simple"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Tổng mã</span>
                <span class="stat-value">{{ $dsKhuyenMai->total() }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #ecfdf5; color: #059669;">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Đang hoạt động</span>
                <span class="stat-value">
                    {{ $dsKhuyenMai->filter(fn($k) => $k->conHieuLuc())->count() }}
                </span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #fef2f2; color: #dc2626;">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Hết hạn / Vô hiệu</span>
                <span class="stat-value">
                    {{ $dsKhuyenMai->filter(fn($k) => !$k->conHieuLuc())->count() }}
                </span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon-wrapper" style="background: #fffbeb; color: #d97706;">
                <i class="fa-solid fa-fire"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Lượt đã dùng</span>
                <span class="stat-value">{{ $dsKhuyenMai->sum('da_dung') }}</span>
            </div>
        </div>
    </div>

    <div class="card-container">
        <!-- Thanh tìm kiếm -->
        <div class="card-header-tools">
            <form action="{{ route('admin.khuyenmai.index') }}" method="GET" class="search-wrapper">
                <div class="search-input-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" placeholder="Tìm mã hoặc tên chương trình..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn-search-action">Tìm kiếm</button>
                @if(request('search'))
                    <a href="{{ route('admin.khuyenmai.index') }}" class="btn-reset-action">Đặt lại</a>
                @endif
            </form>
        </div>

        <!-- Bảng danh sách -->
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 130px;">Mã</th>
                        <th>Chương trình</th>
                        <th style="text-align: center;">Loại giảm</th>
                        <th style="text-align: center;">Giá trị</th>
                        <th style="text-align: center;">Lượt dùng</th>
                        <th style="text-align: center;">Thời hạn</th>
                        <th style="text-align: center;">Trạng thái</th>
                        <th style="text-align: right; width: 160px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dsKhuyenMai as $km)
                        @php
                            $conHieuLuc = $km->conHieuLuc();
                            $hetHan = now()->gt($km->ngay_ket_thuc);
                            $chuaDenHan = now()->lt($km->ngay_bat_dau);
                        @endphp
                        <tr>
                            <td>
                                <span class="product-id-badge" style="background: #eff6ff; color: #1d4ed8; font-size: 13px; letter-spacing: 0.5px;">
                                    {{ $km->ma_khuyen_mai }}
                                </span>
                            </td>
                            <td>
                                <strong style="color: #0f172a;">{{ $km->ten_chuong_trinh }}</strong>
                                @if($km->don_hang_toi_thieu > 0)
                                    <span style="display: block; font-size: 12px; color: #94a3b8; margin-top: 2px;">
                                        Đơn từ {{ number_format($km->don_hang_toi_thieu, 0, ',', '.') }}đ
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($km->loai_giam === 'phan_tram')
                                    <span class="role-badge" style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;">%</span>
                                @else
                                    <span class="role-badge" style="background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;">đ</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($km->loai_giam === 'phan_tram')
                                    <strong style="color: #059669;">{{ $km->gia_tri_giam }}%</strong>
                                    @if($km->giam_toi_da)
                                        <span style="display: block; font-size: 11px; color: #94a3b8;">tối đa {{ number_format($km->giam_toi_da, 0, ',', '.') }}đ</span>
                                    @endif
                                @else
                                    <strong style="color: #059669;">{{ number_format($km->gia_tri_giam, 0, ',', '.') }}đ</strong>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <strong style="color: #d97706;">{{ $km->da_dung }}</strong>
                                <span style="color: #94a3b8;">/{{ $km->so_luong }}</span>
                                <div style="width: 60px; height: 4px; background: #f1f5f9; border-radius: 2px; margin: 4px auto 0;">
                                    <div style="height: 4px; background: #10b981; border-radius: 2px; width: {{ $km->so_luong > 0 ? min(100, ($km->da_dung / $km->so_luong) * 100) : 0 }}%;"></div>
                                </div>
                            </td>
                            <td style="text-align: center; font-size: 12px;">
                                <span style="display: block; color: #475569;">{{ $km->ngay_bat_dau->format('d/m/Y') }}</span>
                                <span style="color: #94a3b8;">→</span>
                                <span style="display: block; color: {{ $hetHan ? '#dc2626' : '#475569' }};">{{ $km->ngay_ket_thuc->format('d/m/Y') }}</span>
                            </td>
                            <td style="text-align: center;">
                                @if($conHieuLuc)
                                    <span class="role-badge" style="background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;">
                                        Đang chạy
                                    </span>
                                @elseif($km->trang_thai == 0)
                                    <span class="role-badge" style="background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0;">
                                        Vô hiệu
                                    </span>
                                @elseif($hetHan)
                                    <span class="role-badge" style="background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;">
                                        Hết hạn
                                    </span>
                                @elseif($chuaDenHan)
                                    <span class="role-badge" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;">
                                        Chưa đến hạn
                                    </span>
                                @else
                                    <span class="role-badge" style="background: #fef9c3; color: #854d0e; border: 1px solid #fef08a;">
                                        Hết lượt
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                    <a href="{{ route('admin.khuyenmai.edit', $km->id) }}" class="btn-action btn-action-edit">
                                        <i class="fa-solid fa-pen-to-square"></i> Sửa
                                    </a>
                                    @if($km->da_dung == 0)
                                        <form action="{{ route('admin.khuyenmai.destroy', $km->id) }}" method="POST" onsubmit="return confirm('Xóa mã {{ $km->ma_khuyen_mai }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-action-delete">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fa-solid fa-ticket-simple"></i></div>
                                    <h3>Chưa có mã khuyến mãi nào</h3>
                                    <p>Nhấn "Thêm mã mới" để tạo chương trình khuyến mãi đầu tiên.</p>
                                    <a href="{{ route('admin.khuyenmai.create') }}" class="btn-reset-action">
                                        <i class="fa-solid fa-plus"></i> Thêm ngay
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($dsKhuyenMai->hasPages())
            <div class="card-footer-pagination">
                {{ $dsKhuyenMai->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
@endsection
