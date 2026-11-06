<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng của tôi - Shop Hoa Quả Sạch</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: #f0f4f0;
            color: #1e2d1f;
            min-height: 100vh;
        }

        .page-wrap {
            max-width: 1100px;
            margin: 20px auto;
            padding: 0 16px 60px;
        }

        /* ===== PAGE HEADING ===== */
        .page-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .page-heading-left { display: flex; align-items: center; gap: 12px; }
        .page-heading-icon {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: 18px;
            flex-shrink: 0;
        }
        .page-heading h1 { font-size: 22px; font-weight: 700; color: #0f1f10; }
        .page-heading p  { font-size: 13px; color: #6b7280; margin-top: 2px; }
        .order-count-badge {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
            border-radius: 20px;
            padding: 5px 14px;
            font-size: 13px;
            font-weight: 600;
        }

        /* ===== FLASH ALERTS ===== */
        .flash-alert {
            display: flex; align-items: center; gap: 10px;
            padding: 14px 18px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 18px;
        }
        .flash-success { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
        .flash-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        /* ===== FILTER TABS ===== */
        .filter-tabs {
            display: flex;
            gap: 6px;
            margin-bottom: 22px;
            flex-wrap: nowrap;
            overflow-x: auto;
            padding-bottom: 4px;
            scrollbar-width: none; /* Firefox */
        }
        .filter-tabs::-webkit-scrollbar { display: none; } /* Chrome/Safari */
        .tab-item {
            text-decoration: none;
            padding: 8px 14px;
            background: #fff;
            color: #4b5563;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid #e5e7eb;
            transition: all 0.18s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .tab-item:hover { background: #f0fdf4; border-color: #86efac; color: #15803d; }
        .tab-item.active {
            background: #16a34a;
            color: #fff;
            border-color: #16a34a;
            box-shadow: 0 2px 10px rgba(22,163,74,0.25);
        }
        .tab-badge {
            background: rgba(255,255,255,0.28);
            border-radius: 10px;
            padding: 1px 7px;
            font-size: 11px;
            font-weight: 700;
        }
        .tab-item:not(.active) .tab-badge {
            background: #f3f4f6;
            color: #6b7280;
        }
        .tab-item.tab-return { color: #7c3aed; border-color: #c4b5fd; }
        .tab-item.tab-return:hover { background: #ede9fe; border-color: #7c3aed; }
        .tab-item.tab-return.active { background: #7c3aed; border-color: #7c3aed; color: #fff; box-shadow: 0 2px 10px rgba(124,58,237,0.25); }

        /* ===== ORDER CARDS ===== */
        .order-list { display: flex; flex-direction: column; gap: 14px; }

        .order-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            transition: box-shadow 0.2s, border-color 0.2s;
        }
        .order-card:hover {
            box-shadow: 0 6px 24px rgba(0,0,0,0.08);
            border-color: #d1fae5;
        }

        /* Card Header */
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            background: #f9fafb;
            border-bottom: 1px solid #f3f4f6;
            flex-wrap: wrap;
            gap: 10px;
        }
        .card-header-left { display: flex; align-items: center; gap: 14px; }
        .order-id {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
        }
        .order-date {
            font-size: 12.5px;
            color: #9ca3af;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12.5px;
            font-weight: 600;
        }
        .s-pending   { background: #fef3c7; color: #92400e; }
        .s-shipping  { background: #dbeafe; color: #1d4ed8; }
        .s-received  { background: #dcfce7; color: #15803d; }
        .s-cancelled { background: #fee2e2; color: #991b1b; }
        .s-return    { background: #ede9fe; color: #5b21b6; }
        .s-returned  { background: #f3e8ff; color: #7c3aed; }
        .s-default   { background: #f1f5f9; color: #475569; }
        .s-confirmed { background: #d1fae5; color: #065f46; }
        .s-failed    { background: #fee2e2; color: #991b1b; }

        .card-body { padding: 16px 20px; }

        .product-preview {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .product-preview:last-of-type { border-bottom: none; }
        .product-preview img {
            width: 52px; height: 52px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            flex-shrink: 0;
        }
        .product-preview-info { flex: 1; min-width: 0; }
        .product-preview-info .pname {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .product-preview-info .pqty {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 3px;
        }
        .product-preview-price {
            font-size: 14px;
            font-weight: 700;
            color: #d80f29;
            white-space: nowrap;
        }

        .more-items {
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            padding: 6px 0;
            font-style: italic;
        }

        /* Return request notice */
        .return-notice {
            margin-top: 12px;
            padding: 10px 14px;
            background: #ede9fe;
            border: 1px solid #c4b5fd;
            border-radius: 8px;
            font-size: 13px;
            color: #5b21b6;
        }
        .return-notice.rejected {
            background: #fef2f2;
            border-color: #fca5a5;
            color: #991b1b;
        }

        /* Card Footer */
        .card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            border-top: 1px solid #f3f4f6;
            background: #fafafa;
            flex-wrap: wrap;
            gap: 12px;
        }
        .card-footer-total {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .total-label { font-size: 13px; color: #6b7280; }
        .total-value { font-size: 18px; font-weight: 700; color: #d80f29; }

        .pt-badge {
            font-size: 12px;
            background: #f1f5f9;
            color: #475569;
            padding: 3px 10px;
            border-radius: 6px;
            font-weight: 600;
        }

        .card-footer-actions { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

        .btn-act {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.18s ease;
            white-space: nowrap;
        }
        .btn-view-detail {
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }
        .btn-view-detail:hover { background: #059669; color: #fff; }

        .btn-cancel-order {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fca5a5;
        }
        .btn-cancel-order:hover { background: #dc2626; color: #fff; }

        .btn-return {
            background: #ede9fe;
            color: #7c3aed;
            border: 1px solid #c4b5fd;
        }
        .btn-return:hover { background: #7c3aed; color: #fff; }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
        }
        .empty-state-icon {
            width: 80px; height: 80px;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 32px;
            color: #cbd5e1;
            margin: 0 auto 20px;
        }
        .empty-state h3 { font-size: 18px; color: #6b7280; font-weight: 600; margin-bottom: 8px; }
        .empty-state p  { font-size: 14px; color: #9ca3af; margin-bottom: 20px; }
        .btn-shop {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #16a34a;
            color: #fff;
            text-decoration: none;
            padding: 11px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            transition: background 0.2s;
        }
        .btn-shop:hover { background: #15803d; }

        /* ===== MODAL TRẢ HÀNG ===== */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        .modal-box {
            background: #fff;
            border-radius: 14px;
            padding: 30px;
            width: 90%;
            max-width: 460px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        .modal-box h3 { font-size: 16px; font-weight: 700; color: #1e1b4b; margin-bottom: 6px; }
        .modal-box p  { font-size: 13px; color: #6b7280; margin-bottom: 14px; }
        .modal-box textarea {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 13.5px;
            resize: vertical;
            min-height: 90px;
            box-sizing: border-box;
            outline: none;
            font-family: inherit;
            transition: border 0.2s;
        }
        .modal-box textarea:focus { border-color: #7c3aed; }
        .modal-actions { display: flex; gap: 10px; margin-top: 14px; }
        .modal-actions .btn-mcancel {
            flex: 1; padding: 10px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            color: #374151;
            font-family: inherit;
        }
        .modal-actions .btn-msubmit {
            flex: 1; padding: 10px;
            background: #7c3aed;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
        }
        .modal-actions .btn-msubmit:hover { background: #6d28d9; }
        .modal-err { color: #dc2626; font-size: 12px; margin-top: 5px; display: none; }

        @media (max-width: 640px) {
            .card-header { flex-direction: column; align-items: flex-start; }
            .card-footer { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>
    @include('client.layouts.header')

    <div class="page-wrap">

        {{-- PAGE HEADING --}}
        <div class="page-heading">
            <div class="page-heading-left">
                <div class="page-heading-icon"><i class="fa-solid fa-bag-shopping"></i></div>
                <div>
                    <h1>Đơn hàng của tôi</h1>
                    <p>Theo dõi và quản lý tất cả đơn hàng của bạn</p>
                </div>
            </div>
            <span class="order-count-badge">
                <i class="fa-solid fa-receipt"></i>
                Tổng: {{ method_exists($donHangs, 'total') ? $donHangs->total() : $donHangs->count() }} đơn
            </span>
        </div>

        {{-- FLASH --}}
        @if(session('success'))
            <div class="flash-alert flash-success">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flash-alert flash-error">
                <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        @php $statusFilter = request()->get('status', ''); @endphp

        {{-- FILTER TABS --}}
        <div class="filter-tabs">
            <a href="{{ route('client.donHangCuaToi') }}" class="tab-item {{ $statusFilter == '' ? 'active' : '' }}">
                <i class="fa-solid fa-border-all"></i> Tất cả
            </a>
            <a href="{{ route('client.donHangCuaToi', ['status' => 'Đang chờ duyệt']) }}"
               class="tab-item {{ $statusFilter == 'Đang chờ duyệt' ? 'active' : '' }}">
                <i class="fa-solid fa-hourglass-half"></i> Chờ duyệt
            </a>
            <a href="{{ route('client.donHangCuaToi', ['status' => 'Đã xác nhận']) }}"
               class="tab-item {{ $statusFilter == 'Đã xác nhận' ? 'active' : '' }}">
                <i class="fa-solid fa-circle-check"></i> Đã xác nhận
            </a>
            <a href="{{ route('client.donHangCuaToi', ['status' => 'Đang vận chuyển']) }}"
               class="tab-item {{ $statusFilter == 'Đang vận chuyển' ? 'active' : '' }}">
                <i class="fa-solid fa-truck-fast"></i> Đang giao
            </a>
            <a href="{{ route('client.donHangCuaToi', ['status' => 'Giao hàng thất bại']) }}"
               class="tab-item {{ $statusFilter == 'Giao hàng thất bại' ? 'active' : '' }}" style="color: #991b1b;">
                <i class="fa-solid fa-triangle-exclamation"></i> Giao thất bại
            </a>
            <a href="{{ route('client.donHangCuaToi', ['status' => 'Đã nhận hàng']) }}"
               class="tab-item {{ $statusFilter == 'Đã nhận hàng' ? 'active' : '' }}">
                <i class="fa-solid fa-circle-check"></i> Đã nhận
            </a>
            <a href="{{ route('client.donHangCuaToi', ['status' => 'Yêu cầu trả hàng']) }}"
               class="tab-item tab-return {{ $statusFilter == 'Yêu cầu trả hàng' ? 'active' : '' }}">
                <i class="fa-solid fa-rotate-left"></i> Yêu cầu trả
            </a>
            <a href="{{ route('client.donHangCuaToi', ['status' => 'Đã trả hàng']) }}"
               class="tab-item {{ $statusFilter == 'Đã trả hàng' ? 'active' : '' }}">
                <i class="fa-solid fa-box-archive"></i> Đã trả hàng
            </a>
            <a href="{{ route('client.donHangCuaToi', ['status' => 'Đã hủy']) }}"
               class="tab-item {{ $statusFilter == 'Đã hủy' ? 'active' : '' }}">
                <i class="fa-solid fa-ban"></i> Đã hủy
            </a>
        </div>

        {{-- CONTENT --}}
        @if($donHangs->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fa-solid fa-box-open"></i></div>
                <h3>Không có đơn hàng nào</h3>
                <p>{{ $statusFilter ? 'Không có đơn hàng nào trong mục này.' : 'Bạn chưa đặt đơn hàng nào.' }}</p>
                <a href="{{ url('/') }}" class="btn-shop">
                    <i class="fa-solid fa-cart-plus"></i> Bắt đầu mua sắm
                </a>
            </div>
        @else
            <div class="order-list">
                @foreach ($donHangs as $dH)
                    @php
                        $statusClass = match($dH->trang_thai) {
                            'Đang chờ duyệt' => 's-pending',
                            'Đang vận chuyển' => 's-shipping',
                            'Đã nhận hàng' => 's-received',
                            'Đã hủy' => 's-cancelled',
                            'Yêu cầu trả hàng' => 's-return',
                            'Đã trả hàng' => 's-returned',
                            'Đã xác nhận' => 's-confirmed',
                            'Giao hàng thất bại' => 's-failed',
                            default => 's-default',
                        };
                        $statusIcon = match($dH->trang_thai) {
                            'Đang chờ duyệt' => 'fa-hourglass-half',
                            'Đang vận chuyển' => 'fa-truck-fast',
                            'Đã nhận hàng' => 'fa-circle-check',
                            'Đã hủy' => 'fa-ban',
                            'Yêu cầu trả hàng' => 'fa-rotate-left',
                            'Đã trả hàng' => 'fa-box-archive',
                            'Đã xác nhận' => 'fa-check-circle',
                            'Giao hàng thất bại' => 'fa-truck-slash',
                            default => 'fa-circle',
                        };
                        $spItems = $dH->ctDonHangs;
                        $showLimit = 2;
                    @endphp

                    <div class="order-card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <span class="order-id"><i class="fa-solid fa-hashtag" style="color:#9ca3af; font-size:12px;"></i>{{ $dH->id }}</span>
                                @if($dH->ngay_dat)
                                    <span class="order-date">
                                        <i class="fa-regular fa-clock"></i>
                                        {{ \Carbon\Carbon::parse($dH->ngay_dat)->format('H:i – d/m/Y') }}
                                    </span>
                                @endif
                            </div>
                            <span class="status-badge {{ $statusClass }}">
                                <i class="fa-solid {{ $statusIcon }}"></i>
                                {{ $dH->trang_thai }}
                            </span>
                        </div>

                        <div class="card-body">
                            @foreach ($spItems->take($showLimit) as $ct)
                                <div class="product-preview">
                                    <img src="{{ asset('uploads/' . ($ct->sanPham->anh ?? 'default.jpg')) }}"
                                         alt="{{ $ct->sanPham->ten ?? 'Sản phẩm' }}">
                                    <div class="product-preview-info">
                                        <div class="pname">{{ $ct->sanPham->ten ?? 'Sản phẩm không còn tồn tại' }}</div>
                                        <div class="pqty">x{{ $ct->so_luong }} &nbsp;·&nbsp; {{ number_format($ct->gia, 0, ',', '.') }}đ/sp</div>
                                    </div>
                                    <div class="product-preview-price">{{ number_format($ct->gia * $ct->so_luong, 0, ',', '.') }}đ</div>
                                </div>
                            @endforeach

                            @if($spItems->count() > $showLimit)
                                <div class="more-items">
                                    <i class="fa-solid fa-ellipsis"></i>
                                    và {{ $spItems->count() - $showLimit }} sản phẩm khác
                                </div>
                            @endif

                            @if($dH->trang_thai === 'Yêu cầu trả hàng' && $dH->ly_do_tra_hang)
                                <div class="return-notice">
                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                    <strong>Yêu cầu trả hàng đang xử lý:</strong> {{ Str::limit($dH->ly_do_tra_hang, 80) }}
                                </div>
                            @endif

                            @if($dH->trang_thai === 'Đã nhận hàng' && $dH->ly_do_tu_choi_tra)
                                <div class="return-notice rejected">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                    <strong>Yêu cầu trả hàng bị từ chối:</strong> {{ Str::limit($dH->ly_do_tu_choi_tra, 80) }}
                                </div>
                            @endif
                        </div>

                        <div class="card-footer">
                            <div class="card-footer-total">
                                <span class="total-label">Tổng thanh toán:</span>
                                <span class="total-value">{{ number_format($dH->tong_tien, 0, ',', '.') }}đ</span>
                                <span class="pt-badge">{{ $dH->pt_thanh_toan ?? 'COD' }}</span>
                            </div>

                            <div class="card-footer-actions">
                                <a href="{{ route('client.chiTietDonHang', $dH->id) }}" class="btn-act btn-view-detail">
                                    <i class="fa-solid fa-eye"></i> Xem chi tiết
                                </a>

                                @if($dH->trang_thai === 'Đang chờ duyệt')
                                    <form action="{{ route('client.huyDonHang', $dH->id) }}" method="POST"
                                          onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn #{{ $dH->id }}? Tồn kho và voucher sẽ được hoàn lại.')"
                                          style="margin:0;">
                                        @csrf
                                        <button type="submit" class="btn-act btn-cancel-order">
                                            <i class="fa-solid fa-xmark"></i> Hủy đơn
                                        </button>
                                    </form>
                                @endif

                                @if($dH->trang_thai === 'Đã nhận hàng')
                                    @if(!$dH->het_han_tra_hang || \Carbon\Carbon::now()->lte(\Carbon\Carbon::parse($dH->het_han_tra_hang)))
                                        <button type="button" class="btn-act btn-return"
                                                onclick="openReturnModal({{ $dH->id }})">
                                            <i class="fa-solid fa-rotate-left"></i> Trả hàng
                                        </button>
                                    @else
                                        <button type="button" class="btn-act btn-return" disabled style="background-color: #e0e0e0; color: #888; border-color: #ccc; cursor: not-allowed;" title="Đã quá thời hạn 1 ngày đổi trả">
                                            <i class="fa-solid fa-rotate-left"></i> Hết hạn đổi trả
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

    <div id="modalTraHang" class="modal-overlay">
    <div class="modal-box">
        <h3><i class="fa-solid fa-rotate-left" style="color:#7c3aed; margin-right:6px;"></i>Yêu cầu trả hàng</h3>
        <p>Mô tả lý do bạn muốn trả hàng. Chúng tôi sẽ xem xét và phản hồi trong 1–3 ngày làm việc.</p>
        
        <!-- Thêm enctype="multipart/form-data" để truyền được file ảnh -->
        <form id="formTraHang" method="POST" action="" enctype="multipart/form-data">
            @csrf
            
            <textarea name="ly_do" id="lyDoInput"
                      placeholder="Ví dụ: Sản phẩm bị hỏng, không đúng mô tả, không đúng số lượng..."
                      maxlength="1000"></textarea>
            <div id="errLyDo" class="modal-err">Vui lòng nhập lý do (ít nhất 10 ký tự).</div>

            <!-- Thêm ô chọn ảnh minh chứng theo phong cách giao diện cũ -->
            <div style="margin-top: 12px;">
                <label style="font-size: 13px; font-weight: 600; color: #374151; display: block; margin-bottom: 4px;">Hình ảnh minh chứng lỗi *</label>
                <input type="file" name="hinh_anh_tra" id="inputAnhTra" accept="image/*" style="font-size: 13px; width: 100%;">
                <div id="errAnh" class="modal-err">Vui lòng chọn hình ảnh minh chứng.</div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-mcancel" onclick="closeReturnModal()">Hủy bỏ</button>
                <button type="submit" class="btn-msubmit" onclick="return validateReturn()">
                    <i class="fa-solid fa-paper-plane"></i> Gửi yêu cầu
                </button>
            </div>
        </form>
    </div>
</div>

    <script>
    const baseReturnUrl = '{{ url("/don-hang/tra-hang") }}';

    function openReturnModal(orderId) {
        document.getElementById('formTraHang').action = baseReturnUrl + '/' + orderId;
        document.getElementById('lyDoInput').value = '';
        document.getElementById('inputAnhTra').value = ''; // Reset lại ô chọn file khi mở modal
        document.getElementById('errLyDo').style.display = 'none';
        document.getElementById('errAnh').style.display = 'none'; // Ẩn lỗi ảnh cũ
        document.getElementById('modalTraHang').style.display = 'flex';
    }

    function closeReturnModal() {
        document.getElementById('modalTraHang').style.display = 'none';
    }

    function validateReturn() {
        const val = document.getElementById('lyDoInput').value.trim();
        const err = document.getElementById('errLyDo');
        
        const inputAnh = document.getElementById('inputAnhTra');
        const errAnh = document.getElementById('errAnh');
        
        let isValid = true;

        // Kiểm tra lý do
        if (val.length < 10) {
            err.style.display = 'block';
            isValid = false;
        } else {
            err.style.display = 'none';
        }

        // Kiểm tra bắt buộc chọn ảnh minh chứng
        if (inputAnh.files.length === 0) {
            errAnh.style.display = 'block';
            isValid = false;
        } else {
            errAnh.style.display = 'none';
        }

        return isValid;
    }

    window.onclick = function(event) {
        const modal = document.getElementById('modalTraHang');
        if (event.target === modal) {
            closeReturnModal();
        }
    };
</script>

    @include('client.layouts.footer')
</body>
</html>