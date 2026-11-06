@extends('admin.layouts.master')

@section('title', 'Chi tiết đơn hàng #' . $donHang->id)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/donHang/read.css') }}">
@endpush

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title-group">
            <h1><i class="fa-solid fa-file-invoice"></i> Chi tiết đơn hàng #{{ $donHang->id }}</h1>
            <p>Toàn bộ thông tin đơn hàng: người nhận, thanh toán và danh sách sản phẩm đã mua.</p>
        </div>
        <div class="order-read-header-actions">
            <a href="{{ route('admin.donhang.inHoaDon', $donHang->id) }}" target="_blank" class="btn-cta-primary btn-print-invoice">
                <i class="fa-solid fa-print"></i> In phiếu giao hàng
            </a>
            <a href="{{ route('admin.donhang.index') }}" class="btn-reset-action">
                <i class="fa-solid fa-arrow-left"></i> Trở về danh sách
            </a>
            <a href="{{ route('admin.donhang.edit', $donHang->id) }}" class="btn-cta-primary">
                <i class="fa-solid fa-pen-to-square"></i> Sửa đơn hàng
            </a>
        </div>
    </div>

    @php
        $tongTienGoc = collect($ctDonHang)->sum(fn($ct) => ($ct->gia ?? 0) * ($ct->so_luong ?? 0));

        $badgeTrangThai = match($donHang->trang_thai) {
            'Đang chờ duyệt'    => ['bg' => '#fef3c7', 'color' => '#92400e'],
            'Đã xác nhận'       => ['bg' => '#e0f7fa', 'color' => '#0369a1'],
            'Đang vận chuyển'    => ['bg' => '#e0f2fe', 'color' => '#0369a1'],
            'Đã nhận hàng'       => ['bg' => '#ecfdf5', 'color' => '#047857'],
            'Đã hủy'             => ['bg' => '#fef2f2', 'color' => '#b91c1c'],
            'Yêu cầu trả hàng'  => ['bg' => '#ede9fe', 'color' => '#5b21b6'],
            'Đã trả hàng'       => ['bg' => '#f3e8ff', 'color' => '#7c3aed'],
            'Giao hàng thất bại' => ['bg' => '#fee2e2', 'color' => '#991b1b'],
            default              => ['bg' => '#f1f5f9', 'color' => '#475569'],
        };
    @endphp

    <div class="order-read-grid">

        <div class="order-read-left-col">
            <div class="card-container">
                <div class="card-header-tools card-header-flex">
                    <div class="card-header-title-group">
                        <span class="product-id-badge order-id-badge">#MÃ ĐƠN: {{ $donHang->id }}</span>
                        <strong class="card-title-strong"><i class="fa-solid fa-circle-info" style="color:#2563eb; margin-right:6px;"></i>Thông tin đơn hàng</strong>
                    </div>
                </div>
                <div class="card-body-padding">
                    <div class="admin-info-row">
                        <span class="admin-info-label">Mã đơn hàng:</span>
                        <span class="admin-info-value"><strong class="strong-text">#{{ $donHang->id }}</strong></span>
                    </div>
                    @if(!empty($donHang->ngay_dat))
                    <div class="admin-info-row">
                        <span class="admin-info-label">Ngày đặt:</span>
                        <span class="admin-info-value">{{ \Carbon\Carbon::parse($donHang->ngay_dat)->format('H:i d/m/Y') }}</span>
                    </div>
                    @endif
                    <div class="admin-info-row">
                        <span class="admin-info-label">Trạng thái đơn:</span>
                        <span class="admin-info-value">
                            <span class="role-badge" style="background: {{ $badgeTrangThai['bg'] }}; color: {{ $badgeTrangThai['color'] }}; border: 1px solid {{ $badgeTrangThai['color'] }}33;">
                                {{ $donHang->trang_thai }}
                            </span>
                        </span>
                    </div>
                    <div class="admin-info-row">
                        <span class="admin-info-label">Trạng thái thanh toán:</span>
                        <span class="admin-info-value">
                            @if($donHang->trang_thai_thanh_toan == 'Đã thanh toán')
                                <span class="role-badge" style="background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;">Đã thanh toán</span>
                            @else
                                <span class="role-badge" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">{{ $donHang->trang_thai_thanh_toan }}</span>
                            @endif
                        </span>
                    </div>
                    <div class="admin-info-row">
                        <span class="admin-info-label">Phương thức thanh toán:</span>
                        <span class="admin-info-value">
                            <span class="payment-method-tag">
                                {{ $donHang->pt_thanh_toan === 'Banking' ? 'Chuyển khoản VietQR' : ($donHang->pt_thanh_toan ?? 'Thanh toán khi nhận hàng (COD)') }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            {{-- Card: Thông tin giao hàng --}}
            <div class="card-container">
                <div class="card-header-tools card-header-flex">
                    <strong class="card-title-strong"><i class="fa-solid fa-truck" style="color:#0284c7; margin-right:6px;"></i>Thông tin giao hàng</strong>
                </div>
                <div class="card-body-padding">
                    <div class="admin-info-row">
                        <span class="admin-info-label">Tên người nhận:</span>
                        <span class="admin-info-value"><strong class="strong-text">{{ $donHang->ten }}</strong></span>
                    </div>
                    <div class="admin-info-row">
                        <span class="admin-info-label">Số điện thoại:</span>
                        <span class="admin-info-value">
                            <a href="tel:{{ $donHang->sdt }}" class="phone-link">{{ $donHang->sdt }}</a>
                        </span>
                    </div>
                    @if(!empty($donHang->email))
                    <div class="admin-info-row">
                        <span class="admin-info-label">Email:</span>
                        <span class="admin-info-value">{{ $donHang->email }}</span>
                    </div>
                    @endif
                    <div class="admin-info-row">
                        <span class="admin-info-label">Địa chỉ nhận hàng:</span>
                        <span class="admin-info-value">{{ $donHang->dia_chi }}</span>
                    </div>
                </div>
            </div>

            {{-- Card: Tổng tiền & Khuyến mãi --}}
            <div class="card-container">
                <div class="card-header-tools card-header-flex">
                    <strong class="card-title-strong"><i class="fa-solid fa-receipt" style="color:#d97706; margin-right:6px;"></i>Tổng tiền & Khuyến mãi</strong>
                </div>
                <div class="card-body-padding">
                    <div class="admin-info-row">
                        <span class="admin-info-label">Tạm tính:</span>
                        <span class="admin-info-value" style="font-weight: 600;">{{ number_format($tongTienGoc, 0, ',', '.') }} đ</span>
                    </div>
                    @if(($donHang->tien_giam ?? 0) > 0)
                    <div class="admin-info-row">
                        <span class="admin-info-label">Mã giảm giá:</span>
                        <span class="admin-info-value">
                            <span class="discount-badge">
                                -{{ number_format($donHang->tien_giam, 0, ',', '.') }} đ
                            </span>
                            @if(!empty($donHang->ma_khuyen_mai))
                                <span class="discount-code-tag">
                                    {{ $donHang->ma_khuyen_mai }}
                                </span>
                            @endif
                        </span>
                    </div>
                    @endif
                    <div class="admin-info-row">
                        <span class="admin-info-label">Phí vận chuyển:</span>
                        <span class="admin-info-value" style="color: {{ ($donHang->phi_van_chuyen ?? 0) == 0 ? '#16a34a' : 'inherit' }}; font-weight: 600;">
                            {{ ($donHang->phi_van_chuyen ?? 0) == 0 ? 'Miễn phí (Freeship)' : number_format($donHang->phi_van_chuyen, 0, ',', '.') . ' đ' }}
                        </span>
                    </div>
                    <div class="order-total-divider">
                        <span class="order-total-label">Tổng thanh toán:</span>
                        <strong class="order-total-value">{{ number_format($donHang->tong_tien, 0, ',', '.') }} đ</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- =================== CỘT PHẢI: DANH SÁCH SẢN PHẨM =================== --}}
        <div class="card-container order-product-card">
            <div class="card-header-tools card-header-flex">
                <strong class="card-title-strong"><i class="fa-solid fa-basket-shopping" style="color:#059669; margin-right:6px;"></i>Sản phẩm đã đặt</strong>
                <div class="result-count-badge">
                    <span>{{ count($ctDonHang) }} mặt hàng</span>
                </div>
            </div>

            <div class="order-product-body">
                @forelse($ctDonHang as $item)
                    @php $thanhTien = ($item->gia ?? 0) * ($item->so_luong ?? 0); @endphp
                    <div class="order-product-item {{ $loop->last ? 'last-item' : '' }}">
                        <div class="order-product-img-box">
                            @if($item->anh_san_pham)
                                <img src="{{ asset('uploads/' . $item->anh_san_pham) }}" alt="{{ $item->ten_san_pham }}"
                                     onerror="this.src='{{ asset('anh/logo.jpg') }}'"
                                     class="order-product-img">
                            @else
                                <img src="{{ asset('anh/logo.jpg') }}" alt="No image"
                                     class="order-product-img">
                            @endif
                        </div>
                        <div class="order-product-info">
                            <div class="order-product-name">{{ $item->ten_san_pham }}</div>
                            <div class="order-product-price">
                                Đơn giá: <strong>{{ number_format($item->gia, 0, ',', '.') }} đ</strong>
                            </div>
                            <div class="order-product-qty">
                                Số lượng: <span class="order-product-qty-badge">x{{ $item->so_luong }}</span>
                            </div>
                        </div>
                        <div class="order-product-subtotal">
                            {{ number_format($thanhTien, 0, ',', '.') }} đ
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class="fa-solid fa-box-open"></i></div>
                        <h3>Không có sản phẩm nào trong đơn hàng</h3>
                    </div>
                @endforelse
            </div>

            @if(count($ctDonHang) > 0)
                <div class="order-product-footer-total">
                    <span class="order-product-footer-label">Tổng giá trị sản phẩm:</span>
                    <strong class="order-product-footer-value">{{ number_format($tongTienGoc, 0, ',', '.') }} đ</strong>
                </div>
            @endif
        </div>

    </div>

    {{-- Nút dưới cùng --}}
    <div class="order-footer-actions">
        <a href="{{ route('admin.donhang.index') }}" class="btn-reset-action">
            <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách
        </a>
        <a href="{{ route('admin.donhang.edit', $donHang->id) }}" class="btn-cta-primary">
            <i class="fa-solid fa-pen-to-square"></i> Cập nhật đơn hàng
        </a>
    </div>

@endsection