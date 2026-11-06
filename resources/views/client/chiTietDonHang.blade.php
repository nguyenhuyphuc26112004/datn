<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng #{{ $donHang->id }}</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
          integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        body { background-color: #f8f9fa;  }

        .ct-wrapper { max-width: 800px; margin: 40px auto; padding: 0 16px 60px; }

        /* Breadcrumb */
        .breadcrumb { font-size: 13px; color: #888; margin-bottom: 20px; }
        .breadcrumb a { color: #28a745; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        .breadcrumb span { margin: 0 6px; }

        .ct-wrapper h2 {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 24px;
            border-left: 4px solid #28a745;
            padding-left: 12px;
        }

        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            border: 1px solid #e9ecef;
            padding: 24px;
            margin-bottom: 20px;
        }
        .card h4 {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            color: #28a745;
            letter-spacing: 0.8px;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
        }

        /* Thông tin giao hàng */
        .info-row { display: flex; gap: 8px; margin-bottom: 10px; font-size: 14px; }
        .info-row .label { color: #888; min-width: 140px; flex-shrink: 0; }
        .info-row .value { color: #333; font-weight: 600; }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-cho-xu-ly { background: #fff3cd; color: #856404; }
        .badge-dang-giao   { background: #cce5ff; color: #004085; }
        .badge-da-giao     { background: #d4edda; color: #155724; }
        .badge-huy        { background: #f8d7da; color: #721c24; }
        .badge-chua-tt    { background: #ffeeba; color: #856404; }
        .badge-da-tt      { background: #d4edda; color: #155724; }
        .badge-voucher    { background: #e2e3e5; color: #383d41; font-family: monospace; }
        .badge-tra-hang   { background: #ede9fe; color: #5b21b6; }
        .badge-da-tra     { background: #f3e8ff; color: #7c3aed; }

        /* Sản phẩm */
        .order-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f3f5;
        }
        .order-item:last-child { border-bottom: none; }
        .order-item img { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #dee2e6; flex-shrink: 0; }
        .order-item-info { flex: 1; }
        .order-item-info .ten { font-size: 14px; font-weight: bold; color: #333; }
        .order-item-info .qty { font-size: 13px; color: #888; margin-top: 4px; }
        .order-item-info .don-gia { font-size: 12px; color: #aaa; margin-top: 2px; }
        .order-item-price { font-size: 15px; font-weight: bold; color: #d9534f; white-space: nowrap; }

        /* Bảng tổng tiền */
        .summary-box {
            margin-top: 16px;
            padding-top: 12px;
            border-top: 2px dashed #e9ecef;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            font-size: 14px;
            color: #555;
        }
        .summary-row.discount {
            color: #28a745;
            font-weight: 600;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            margin-top: 8px;
            border-top: 1px solid #e9ecef;
        }
        .total-row .total-label { font-size: 16px; color: #333; font-weight: bold; }
        .total-row .total-value { font-size: 22px; font-weight: bold; color: #d9534f; }

        /* QR Banking */
        .qr-box {
            margin-top: 14px;
            padding: 16px;
            background: #f0f7ff;
            border: 1px dashed #007bff;
            border-radius: 8px;
            text-align: center;
        }
        .qr-box p { font-size: 13px; color: #555; margin-bottom: 10px; }
        .qr-box img { width: 160px; height: 160px; object-fit: contain; border-radius: 8px; }
        .qr-code-text {
            display: inline-block;
            margin-top: 10px;
            background: #fff;
            border: 1px solid #007bff;
            padding: 5px 14px;
            border-radius: 6px;
            font-weight: bold;
            color: #007bff;
            font-size: 14px;
            letter-spacing: 1px;
        }

        /* Nút quay lại */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #28a745;
            color: #fff;
            border: none;
            padding: 10px 24px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-back:hover { background: #218838; }

        /* Nút trả hàng */
        .btn-tra-hang {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #7c3aed;
            color: #fff;
            border: none;
            padding: 10px 24px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
            margin-left: 10px;
        }
        .btn-tra-hang:hover { background: #6d28d9; }

        /* Modal trả hàng */
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
            border-radius: 12px;
            padding: 32px;
            width: 90%;
            max-width: 480px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        .modal-box h3 {
            font-size: 17px;
            font-weight: bold;
            color: #1e1b4b;
            margin-bottom: 8px;
        }
        .modal-box p {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 16px;
        }
        .modal-box textarea {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 14px;
            resize: vertical;
            min-height: 100px;
            box-sizing: border-box;
            outline: none;
            transition: border 0.2s;
        }
        .modal-box textarea:focus { border-color: #7c3aed; }
        .modal-actions {
            display: flex;
            gap: 10px;
            margin-top: 16px;
        }
        .modal-actions .btn-cancel {
            flex: 1;
            padding: 10px;
            border: 1px solid #d1d5db;
            background: #f9fafb;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            color: #374151;
        }
        .modal-actions .btn-submit {
            flex: 1;
            padding: 10px;
            background: #7c3aed;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }
        .modal-actions .btn-submit:hover { background: #6d28d9; }
        .error-msg { color: #dc2626; font-size: 12px; margin-top: 4px; display: none; }
    </style>
</head>
<body>

    @include('client.layouts.header')

    <div class="ct-wrapper">

        <div class="breadcrumb">
            <a href="{{ route('client.home') }}">Trang chủ</a>
            <span>›</span>
            <a href="{{ route('client.donHangCuaToi') }}">Đơn hàng của tôi</a>
            <span>›</span>
            Chi tiết đơn #{{ $donHang->id }}
        </div>

        <h2><i class="fa-solid fa-receipt" style="color:#28a745; margin-right:8px;"></i>Chi tiết đơn hàng #{{ $donHang->id }}</h2>
        @if(session('success'))
            <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                <i class="fa-solid fa-check-circle" style="margin-right: 6px;"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                <i class="fa-solid fa-triangle-exclamation" style="margin-right: 6px;"></i> {{ session('error') }}
            </div>
        @endif
        @php
            $badgeTrangThai = match($donHang->trang_thai) {
                'Chờ xử lý'          => 'badge-cho-xu-ly',
                'Đang giao'           => 'badge-dang-giao',
                'Đã giao'             => 'badge-da-giao',
                'Đã nhận hàng'        => 'badge-da-giao',
                'Đã hủy'              => 'badge-huy',
                'Yêu cầu trả hàng'   => 'badge-tra-hang',
                'Đã trả hàng'        => 'badge-da-tra',
                default               => 'badge-cho-xu-ly',
            };
            $badgeTT = $donHang->trang_thai_thanh_toan === 'Đã thanh toán' ? 'badge-da-tt' : 'badge-chua-tt';
            
            // Tính tổng tiền gốc trước khi giảm giá
            $tongTienGoc = $donHang->ctDonHangs->sum(fn($ct) => $ct->gia * $ct->so_luong);
        @endphp

        {{-- THÔNG TIN ĐƠN HÀNG --}}
        <div class="card">
            <h4><i class="fa-solid fa-circle-info"></i> Thông tin đơn hàng</h4>

            <div class="info-row">
                <span class="label">Mã đơn hàng:</span>
                <span class="value">#{{ $donHang->id }}</span>
            </div>
            @if($donHang->ngay_dat)
            <div class="info-row">
                <span class="label">Ngày đặt:</span>
                <span class="value">{{ \Carbon\Carbon::parse($donHang->ngay_dat)->format('d/m/Y H:i') }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="label">Trạng thái đơn:</span>
                <span class="value"><span class="badge {{ $badgeTrangThai }}">{{ $donHang->trang_thai }}</span></span>
            </div>
            <div class="info-row">
                <span class="label">Trạng thái thanh toán:</span>
                <span class="value"><span class="badge {{ $badgeTT }}">{{ $donHang->trang_thai_thanh_toan }}</span></span>
            </div>
            <div class="info-row">
                <span class="label">Phương thức thanh toán:</span>
                <span class="value">{{ $donHang->pt_thanh_toan === 'ONLINE' ? 'Chuyển khoản VietQR' : 'Tiền mặt (COD)' }}</span>
            </div>
        </div>

        {{-- THÔNG TIN GIAO HÀNG --}}
        <div class="card">
            <h4><i class="fa-solid fa-truck"></i> Thông tin giao hàng</h4>

            <div class="info-row">
                <span class="label">Người nhận:</span>
                <span class="value">{{ $donHang->ten }}</span>
            </div>
            <div class="info-row">
                <span class="label">Số điện thoại:</span>
                <span class="value">{{ $donHang->sdt }}</span>
            </div>
            <div class="info-row">
                <span class="label">Email:</span>
                <span class="value">{{ $donHang->email }}</span>
            </div>
            <div class="info-row">
                <span class="label">Địa chỉ:</span>
                <span class="value">{{ $donHang->dia_chi }}</span>
            </div>
        </div>

        {{-- DANH SÁCH SẢN PHẨM --}}
        <div class="card">
            <h4><i class="fa-solid fa-basket-shopping"></i> Sản phẩm đã đặt</h4>

            @foreach($donHang->ctDonHangs as $ct)
                <div class="order-item">
                    <img src="{{ asset('uploads/' . ($ct->sanPham->anh ?? 'default.jpg')) }}" alt="{{ $ct->sanPham->ten ?? 'Sản phẩm' }}">
                    <div class="order-item-info">
                        <div class="ten">{{ $ct->sanPham->ten ?? 'Sản phẩm không còn tồn tại' }}</div>
                        <div class="qty">Số lượng: {{ $ct->so_luong }}</div>
                        <div class="don-gia">Đơn giá: {{ number_format($ct->gia, 0, ',', '.') }}đ</div>
                    </div>
                    <div class="order-item-price">{{ number_format($ct->gia * $ct->so_luong, 0, ',', '.') }}đ</div>
                </div>
            @endforeach

            {{-- BẢNG TÍNH TIỀN VÀ MÃ GIẢM GIÁ --}}
            <div class="summary-box">
                <div class="summary-row">
                    <span>Tạm tính:</span>
                    <span>{{ number_format($tongTienGoc, 0, ',', '.') }}đ</span>
                </div>

                @if($donHang->ma_khuyen_mai)
                    <div class="summary-row discount">
                        <span><i class="fa-solid fa-ticket"></i> Mã giảm giá (<span class="badge badge-voucher">{{ $donHang->ma_khuyen_mai }}</span>):</span>
                        <span>-{{ number_format($donHang->tien_giam ?? 0, 0, ',', '.') }}đ</span>
                    </div>
                @endif

                <div class="summary-row">
                    <span>Phí vận chuyển:</span>
                    <span style="color: {{ ($donHang->phi_van_chuyen ?? 0) == 0 ? '#16a34a' : 'inherit' }};">
                        {{ ($donHang->phi_van_chuyen ?? 0) == 0 ? 'Miễn phí (Freeship)' : number_format($donHang->phi_van_chuyen, 0, ',', '.') . 'đ' }}
                    </span>
                </div>

                <div class="total-row">
                    <span class="total-label">Tổng thanh toán:</span>
                    <span class="total-value">{{ number_format($donHang->tong_tien, 0, ',', '.') }}đ</span>
                </div>
            </div>
        </div>

        @if(in_array($donHang->pt_thanh_toan, ['ONLINE', 'Banking']) && $donHang->trang_thai_thanh_toan !== 'Đã thanh toán' && $donHang->ma_chuyen_khoan && $donHang->trang_thai !== 'Đã hủy')
            @php
                $qrUrl = "https://img.vietqr.io/image/MB-0368334112-compact2.png?amount={$donHang->tong_tien}&addInfo={$donHang->ma_chuyen_khoan}&accountName=" . urlencode("NGUYEN HUY PHUC");
            @endphp
            <div class="card">
                <h4><i class="fa-solid fa-qrcode"></i> Thanh toán chuyển khoản</h4>
                <div class="qr-box">
                    <p>📲 Quét mã QR để hoàn tất thanh toán</p>
                    <img src="{{ $qrUrl }}" alt="QR Code">
                    <br>
                    <span class="qr-code-text">{{ $donHang->ma_chuyen_khoan }}</span>
                    <p style="font-size:12px; color:#888; margin-top:10px;">Vui lòng chuyển đúng nội dung để đơn hàng được xác nhận nhanh hơn.</p>
                    @if($donHang->het_han_thanh_toan && \Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($donHang->het_han_thanh_toan)))
                        <p style="font-size:12px; color:#c2410c; margin-top:8px; font-weight: 600;">
                            ⏱️ Hạn thanh toán: trước {{ \Carbon\Carbon::parse($donHang->het_han_thanh_toan)->format('H:i d/m/Y') }} (quá hạn đơn sẽ tự động hủy hoàn tồn kho).
                        </p>
                    @endif
                </div>
            </div>
        @endif

        <a href="{{ route('client.donHangCuaToi') }}" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách đơn hàng
        </a>

        @if($donHang->trang_thai === 'Đã nhận hàng')
            @if(!$donHang->het_han_tra_hang || \Carbon\Carbon::now()->lte(\Carbon\Carbon::parse($donHang->het_han_tra_hang)))
                <button type="button" class="btn-tra-hang" onclick="document.getElementById('modalTraHang').style.display='flex'">
                    <i class="fa-solid fa-rotate-left"></i> Yêu cầu trả hàng
                </button>
            @else
                <button type="button" class="btn-tra-hang" disabled style="background-color: #ccc; cursor: not-allowed;" title="Đã quá thời hạn 1 ngày đổi trả">
                    <i class="fa-solid fa-rotate-left"></i> Đã hết hạn đổi trả
                </button>
            @endif
        @endif

        @if($donHang->trang_thai === 'Yêu cầu trả hàng')
            <div style="margin-top: 20px; padding: 16px; background: #ede9fe; border: 1px solid #c4b5fd; border-radius: 8px;">
                <p style="font-size: 14px; color: #5b21b6; font-weight: 600; margin: 0 0 6px;">
                    <i class="fa-solid fa-clock-rotate-left"></i> Yêu cầu trả hàng đang được xử lý
                </p>
                <p style="font-size: 13px; color: #6d28d9; margin: 0;">Lý do: {{ $donHang->ly_do_tra_hang }}</p>
            </div>
        @endif

        @if($donHang->trang_thai === 'Đã nhận hàng' && $donHang->ly_do_tu_choi_tra)
            <div style="margin-top: 20px; padding: 16px; background: #fef2f2; border: 1px solid #fca5a5; border-radius: 8px;">
                <p style="font-size: 14px; color: #991b1b; font-weight: 600; margin: 0 0 6px;">
                    <i class="fa-solid fa-circle-xmark"></i> Yêu cầu trả hàng đã bị từ chối
                </p>
                <p style="font-size: 13px; color: #b91c1c; margin: 0;">Lý do từ chối: {{ $donHang->ly_do_tu_choi_tra }}</p>
            </div>
        @endif

        @if($donHang->trang_thai === 'Đã trả hàng')
            <div style="margin-top: 20px; padding: 16px; background: #f3e8ff; border: 1px solid #d8b4fe; border-radius: 8px;">
                <p style="font-size: 14px; color: #7c3aed; font-weight: 600; margin: 0;">
                    <i class="fa-solid fa-check-circle"></i> Đơn hàng đã được trả thành công. Kho hàng đã được hoàn lại.
                </p>
            </div>
        @endif
    </div>

    <!-- <div id="modalTraHang" class="modal-overlay">
        <div class="modal-box">
            <h3><i class="fa-solid fa-rotate-left" style="color:#7c3aed;"></i> Yêu cầu trả hàng</h3>
            <p>Vui lòng mô tả lý do bạn muốn trả hàng. Chúng tôi sẽ xem xét và phản hồi trong vòng 1-3 ngày làm việc.</p>

            <form id="formTraHang" method="POST" action="{{ route('client.yeuCauTraHang', $donHang->id) }}">
                @csrf
                <textarea name="ly_do" id="lyDoTraHang" placeholder="Ví dụ: Sản phẩm bị hỏng, không đúng mô tả, không vừa size..." maxlength="1000"></textarea>
                <div id="errorLyDo" class="error-msg">Vui lòng nhập lý do trả hàng (ít nhất 10 ký tự).</div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="document.getElementById('modalTraHang').style.display='none'">Hủy bỏ</button>
                    <button type="submit" class="btn-submit" onclick="return validateTraHang()"><i class="fa-solid fa-paper-plane"></i> Gửi yêu cầu</button>
                </div>
            </form>
        </div>
    </div> -->
<div id="modalTraHang" class="modal-overlay">
    <div class="modal-box">
        <h3><i class="fa-solid fa-rotate-left" style="color:#7c3aed;"></i> Yêu cầu trả hàng</h3>
        <p>Vui lòng mô tả lý do bạn muốn trả hàng. Chúng tôi sẽ xem xét và phản hồi trong vòng 1-3 ngày làm việc.</p>

        <!-- Thêm enctype="multipart/form-data" để upload được ảnh -->
        <form id="formTraHang" method="POST" action="{{ route('client.yeuCauTraHang', $donHang->id) }}" enctype="multipart/form-data">
            @csrf
            
            <textarea name="ly_do" id="lyDoTraHang" placeholder="Ví dụ: Sản phẩm bị hỏng, không đúng mô tả..." maxlength="1000"></textarea>
            <div id="errorLyDo" class="error-msg">Vui lòng nhập lý do trả hàng (ít nhất 10 ký tự).</div>

            <!-- Thêm ô chọn ảnh theo form giao diện cũ -->
            <div style="margin-top: 12px;">
                <label style="font-size: 13px; font-weight: 600; color: #374151; display: block; margin-bottom: 4px;">Hình ảnh minh chứng lỗi *</label>
                <input type="file" name="hinh_anh_tra" id="inputAnhTra" accept="image/*" style="font-size: 13px; width: 100%;">
                <div id="errorAnh" class="error-msg">Vui lòng chọn hình ảnh minh chứng.</div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="document.getElementById('modalTraHang').style.display='none'">Hủy bỏ</button>
                <button type="submit" class="btn-submit" onclick="return validateTraHang()"><i class="fa-solid fa-paper-plane"></i> Gửi yêu cầu</button>
            </div>
        </form>
    </div>
</div>

<script>
    function validateTraHang() {
        const lyDo = document.getElementById('lyDoTraHang').value.trim();
        const errLyDo = document.getElementById('errorLyDo');
        
        const inputAnh = document.getElementById('inputAnhTra');
        const errAnh = document.getElementById('errorAnh');
        
        let isValid = true;

        if (lyDo.length < 10) {
            errLyDo.style.display = 'block';
            isValid = false;
        } else {
            errLyDo.style.display = 'none';
        }

        if (inputAnh.files.length === 0) {
            errAnh.style.display = 'block';
            isValid = false;
        } else {
            errAnh.style.display = 'none';
        }

        return isValid;
    }

    // Đóng modal khi click bên ngoài
    document.getElementById('modalTraHang').addEventListener('click', function(e) {
        if (e.target === this) this.style.display = 'none';
    });
</script>

    <
    <script>
    function previewImage(event) {
        const reader = new FileReader();
        const imageField = document.getElementById('imgPreview');
        const previewContainer = document.getElementById('previewContainer');
        
        reader.onload = function(){
            if(reader.readyState == 2){
                imageField.src = reader.result;
                previewContainer.style.display = 'block';
            }
        }
        if(event.target.files[0]){
            reader.readAsDataURL(event.target.files[0]);
        }
    }

    // Hàm kiểm tra validate trước khi submit form
    function validateTraHang() {
        const lyDo = document.getElementById('lyDoTraHang').value.trim();
        const errLyDo = document.getElementById('errorLyDo');
        
        const inputAnh = document.getElementById('inputAnhTra');
        const errAnh = document.getElementById('errorAnh');
        
        let isValid = true;

        // Kiểm tra lý do (ít nhất 10 ký tự)
        if (lyDo.length < 10) {
            errLyDo.style.display = 'block';
            isValid = false;
        } else {
            errLyDo.style.display = 'none';
        }

        // Kiểm tra bắt buộc phải chọn ảnh
        if (inputAnh.files.length === 0) {
            errAnh.style.display = 'block';
            isValid = false;
        } else {
            errAnh.style.display = 'none';
        }

        return isValid;
    }

    // Đóng modal khi click bên ngoài
    document.getElementById('modalTraHang').addEventListener('click', function(e) {
        if (e.target === this) this.style.display = 'none';
    });
</script>

</body>
</html>