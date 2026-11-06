@extends('admin.layouts.master')

@section('title', 'Chi tiết sản phẩm - ' . $product->ten)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/sanPham/read.css') }}">
@endpush

@section('content')
    <div class="product-read-header">
        <div class="product-read-title-group">
            <h1><i class="fa-solid fa-box-open"></i> Chi tiết sản phẩm</h1>
            <p>Xem thông tin thuộc tính, tồn kho, hạn sử dụng và mức giá của sản phẩm #{{ $product->id }}.</p>
        </div>
        <div>
            <a href="{{ route('admin.sanpham.index') }}" class="btn-reset-action">
                <i class="fa-solid fa-arrow-left"></i> Trở về danh sách
            </a>
        </div>
    </div>

    <div class="product-read-card">
        <div class="product-read-card-header">
            <div class="product-read-header-left">
                <span class="product-read-id-badge">#SP: {{ $product->id }}</span>
                <strong class="product-read-name-strong">{{ $product->ten }}</strong>
            </div>
            
            <a href="{{ route('admin.sanpham.edit', $product->id) }}" class="btn-action btn-action-edit btn-custom-action">
                <i class="fa-solid fa-pen-to-square"></i> Sửa sản phẩm
            </a>
        </div>

        <div class="product-read-body">
            <div class="product-info-grid">
                <div>
                    <div class="product-image-box">
                        @if($product->anh)
                            <img src="{{ asset('uploads/' . $product->anh) }}" alt="{{ $product->ten }}" 
                                 onerror="this.src='{{ asset('anh/logo.jpg') }}'">
                        @else
                            <img src="{{ asset('anh/logo.jpg') }}" alt="No image">
                        @endif
                    </div>
                </div>

                <div>
                    <table class="product-attrs-table">
                        <tbody>
                            <tr>
                                <th>Tên sản phẩm</th>
                                <td><strong>{{ $product->ten }}</strong></td>
                            </tr>
                            <tr>
                                <th>Phân loại danh mục</th>
                                <td>
                                    <span class="badge-category">
                                        {{ $product->loai ?? 'Trái cây' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Hạn sử dụng</th>
                                <td>
                                    @if($product->ngay_het_han)
                                        @php
                                            $hsd = \Carbon\Carbon::parse($product->ngay_het_han);
                                            $today = \Carbon\Carbon::today();
                                            $isExpired = $hsd->lt($today);
                                            $isNearExpiry = (!$isExpired && $hsd->diffInDays($today, false) >= -2 && $hsd->gte($today));
                                        @endphp
                                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                            <strong>{{ $hsd->format('d/m/Y') }}</strong>
                                            @if($isExpired)
                                                <span class="badge-hsd-expired">Đã quá hạn</span>
                                            @elseif($isNearExpiry)
                                                <span class="badge-hsd-near">Sắp hết hạn</span>
                                            @else
                                                <span class="badge-hsd-good">Còn hạn tốt</span>
                                            @endif
                                        </div>
                                    @else
                                        <span style="color: #94a3b8; font-style: italic;">Không có thông tin</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Đơn giá bán lẻ</th>
                                <td>
                                    <span class="product-price-highlight">
                                        {{ number_format($product->gia, 0, ',', '.') }} VNĐ
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Đơn vị tính</th>
                                <td>{{ $product->don_vi ?? 'Kg' }}</td>
                            </tr>
                            <tr>
                                <th>Tồn kho</th>
                                <td>
                                    @if(($product->so_luong ?? 0) > 0)
                                        <span class="stock-in-stock">
                                            {{ $product->so_luong }} {{ $product->don_vi ?? '' }}
                                        </span>
                                    @else
                                        <span class="stock-out-stock">
                                            Hết hàng (0)
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Trạng thái bán</th>
                                <td>
                                    @if(($product->trang_thai ?? 1) == 1 && ($product->so_luong ?? 0) > 0)
                                        <span class="role-badge" style="background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; padding: 3px 8px; font-size: 11.5px;">
                                            Đang kinh doanh
                                        </span>
                                    @else
                                        <span class="role-badge" style="background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; padding: 3px 8px; font-size: 11.5px;">
                                            Ngừng bán / Hết hàng
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="batch-list-container">
                <div class="batch-list-header">
                    <h4 class="batch-list-title">
                        <i class="fa-solid fa-boxes-stacked" style="color: #2563eb;"></i> Danh sách Lô hàng tồn kho ({{ $product->loHangs->count() }} lô)
                    </h4>
                    <span class="batch-list-hint">Xuất kho tự động theo nguyên tắc FEFO (Hết hạn sớm xuất trước)</span>
                </div>
                <div class="batch-table-wrapper">
                    <table class="batch-table">
                        <thead>
                            <tr>
                                <th>Mã lô</th>
                                <th>Hạn sử dụng</th>
                                <th style="text-align: center;">SL Nhập</th>
                                <th style="text-align: center;">Còn lại</th>
                                @if(auth()->user()->isAdmin())
                                    <th style="text-align: right;">Giá nhập</th>
                                @endif
                                <th style="text-align: center;">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($product->loHangs as $lo)
                                @php
                                    $badge = $lo->trang_thai_badge;
                                @endphp
                                <tr>
                                    <td style="font-weight: 700; color: #1e293b; font-family: monospace;">
                                        {{ $lo->ma_lo }}
                                    </td>
                                    <td style="font-weight: 600; color: #0f172a;">
                                        {{ $lo->ngay_het_han ? \Carbon\Carbon::parse($lo->ngay_het_han)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td style="text-align: center; color: #64748b;">
                                        {{ $lo->so_luong_nhap }} {{ $product->don_vi }}
                                    </td>
                                    <td style="text-align: center;">
                                        <strong style="color: {{ $lo->so_luong_con_lai > 0 ? '#059669' : '#dc2626' }};">
                                            {{ $lo->so_luong_con_lai }}
                                        </strong> {{ $product->don_vi }}
                                    </td>
                                    @if(auth()->user()->isAdmin())
                                        <td style="text-align: right; color: #475569;">
                                            {{ number_format($lo->gia_nhap, 0, ',', '.') }} đ
                                        </td>
                                    @endif
                                    <td style="text-align: center;">
                                        <span style="font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 6px; background: {{ $badge['bg'] }}; color: {{ $badge['color'] }}; border: 1px solid {{ $badge['border'] }}; display: inline-block;">
                                            {{ $badge['label'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->isAdmin() ? 6 : 5 }}" style="padding: 20px; text-align: center; color: #94a3b8;">
                                        Chưa có lô hàng nào được ghi nhận cho sản phẩm này.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="product-description-box">
                <h4 class="product-description-title">
                    <i class="fa-solid fa-circle-info" style="color: #2563eb;"></i> Mô tả chi tiết sản phẩm
                </h4>
                <div class="product-description-content">
                    {{ $product->mo_ta ?? 'Chưa có thông tin mô tả chi tiết cho sản phẩm này.' }}
                </div>
            </div>

            <div class="product-read-footer">
                <a href="{{ route('admin.sanpham.index') }}" class="btn-reset-action btn-custom-action">
                    <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách
                </a>
                
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.sanpham.edit', $product->id) }}" class="btn-cta-primary btn-custom-action">
                        <i class="fa-solid fa-pen-to-square"></i> Cập nhật sản phẩm
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection