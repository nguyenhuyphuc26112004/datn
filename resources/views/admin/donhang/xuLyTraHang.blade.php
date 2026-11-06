@extends('admin.layouts.master')

@section('title', 'Xử lý yêu cầu trả hàng #' . $donHang->id)

@push('styles')
<style>
    .return-wrapper { max-width: 820px; margin: 0 auto; }

    .return-header-card {
        background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
        border-radius: 14px;
        padding: 24px 28px;
        color: #fff;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .return-header-card .icon-wrap {
        width: 52px; height: 52px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    .return-header-card h2 { font-size: 18px; font-weight: 700; margin: 0 0 4px; }
    .return-header-card p  { font-size: 13px; margin: 0; opacity: 0.85; }

    .info-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        margin-bottom: 20px;
    }
    .info-card h4 {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        color: #7c3aed;
        margin: 0 0 16px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e2e8f0;
    }

    .info-row { display: flex; gap: 12px; margin-bottom: 10px; font-size: 14px; }
    .info-row .lbl { color: #64748b; min-width: 160px; flex-shrink: 0; }
    .info-row .val { color: #0f172a; font-weight: 600; }

    /* Lý do trả hàng nổi bật */
    .reason-box {
        background: #ede9fe;
        border: 1px solid #c4b5fd;
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }
    .reason-box .reason-label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6d28d9;
        margin-bottom: 8px;
    }
    .reason-box .reason-text {
        font-size: 14px;
        color: #3b0764;
        line-height: 1.6;
    }

    /* Bảng sản phẩm */
    .product-table { width: 100%; border-collapse: collapse; }
    .product-table th {
        background: #f8fafc;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        padding: 10px 12px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
    }
    .product-table td {
        padding: 12px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13.5px;
        color: #334155;
        vertical-align: middle;
    }
    .product-table tr:last-child td { border-bottom: none; }
    .product-table img { width: 48px; height: 48px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0; }

    /* Action area */
    .action-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        margin-bottom: 20px;
    }
    .action-card h4 {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        color: #334155;
        margin: 0 0 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e2e8f0;
    }

    .action-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

    /* Approve panel */
    .approve-panel {
        background: #f0fdf4;
        border: 1px solid #86efac;
        border-radius: 10px;
        padding: 20px;
    }
    .approve-panel h5 { font-size: 14px; font-weight: 700; color: #15803d; margin: 0 0 8px; }
    .approve-panel p  { font-size: 12.5px; color: #166534; margin: 0 0 16px; line-height: 1.5; }

    /* Reject panel */
    .reject-panel {
        background: #fef2f2;
        border: 1px solid #fca5a5;
        border-radius: 10px;
        padding: 20px;
    }
    .reject-panel h5 { font-size: 14px; font-weight: 700; color: #991b1b; margin: 0 0 8px; }
    .reject-panel p  { font-size: 12.5px; color: #b91c1c; margin: 0 0 12px; line-height: 1.5; }
    .reject-panel textarea {
        width: 100%;
        border: 1px solid #fca5a5;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 13.5px;
        resize: vertical;
        min-height: 90px;
        box-sizing: border-box;
        outline: none;
        background: #fff;
        transition: border 0.2s;
    }
    .reject-panel textarea:focus { border-color: #dc2626; }

    .btn-approve {
        width: 100%;
        padding: 11px;
        background: #16a34a;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
    }
    .btn-approve:hover { background: #15803d; }

    .btn-reject {
        width: 100%;
        margin-top: 10px;
        padding: 11px;
        background: #dc2626;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
    }
    .btn-reject:hover { background: #b91c1c; }

    .btn-back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #64748b;
        font-size: 13.5px;
        text-decoration: none;
        font-weight: 600;
        padding: 8px 0;
        transition: color 0.15s;
    }
    .btn-back-link:hover { color: #0f172a; }

    .err-text { font-size: 12px; color: #dc2626; margin-top: 5px; display: none; }

    @media (max-width: 640px) {
        .action-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
    <div class="return-wrapper">

        {{-- Breadcrumb --}}
        <a href="{{ route('admin.donhang.index') }}" class="btn-back-link" style="margin-bottom: 16px; display: inline-flex;">
            <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách đơn hàng
        </a>

        {{-- Header --}}
        <div class="return-header-card">
            <div class="icon-wrap"><i class="fa-solid fa-rotate-left"></i></div>
            <div>
                <h2>Xử lý yêu cầu trả hàng – Đơn #{{ $donHang->id }}</h2>
                <p>Khách hàng <strong>{{ $donHang->ten }}</strong> ({{ $donHang->sdt }}) đã gửi yêu cầu trả hàng.</p>
            </div>
        </div>

        {{-- Flash --}}
        @if(session('error'))
            <div class="alert-box alert-box-error" style="margin-bottom:16px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Lý do trả hàng của khách --}}
        <div class="reason-box">
            <div class="reason-label"><i class="fa-solid fa-comment-dots"></i> Lý do khách muốn trả hàng</div>
            <div class="reason-text">{{ $donHang->ly_do_tra_hang ?? 'Không có lý do.' }}</div>
        </div>
        @if($donHang->hinh_anh_tra)
        <div class="info-card">
            <h4><i class="fa-solid fa-image"></i> Hình ảnh minh chứng từ khách hàng</h4>
            <div style="display: flex; align-items: center; gap: 16px;">
                <a href="{{ asset($donHang->hinh_anh_tra) }}" target="_blank" title="Bấm để xem ảnh lớn">
                    <img src="{{ asset($donHang->hinh_anh_tra) }}" alt="Ảnh minh chứng trả hàng" style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1; cursor: pointer; transition: transform 0.2s;">
                </a>
                <div>
                    <a href="{{ asset($donHang->hinh_anh_tra) }}" target="_blank" class="btn-back-link" style="font-size: 13px; color: #7c3aed;">
                        <i class="fa-solid fa-up-right-from-square"></i> Xem ảnh kích thước đầy đủ
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- Thông tin đơn hàng --}}
        <div class="info-card">
            <h4><i class="fa-solid fa-circle-info"></i> Thông tin đơn hàng</h4>
            <div class="info-row">
                <span class="lbl">Mã đơn:</span>
                <span class="val">#{{ $donHang->id }}</span>
            </div>
            <div class="info-row">
                <span class="lbl">Khách hàng:</span>
                <span class="val">{{ $donHang->ten }} – {{ $donHang->sdt }}</span>
            </div>
            <div class="info-row">
                <span class="lbl">Địa chỉ:</span>
                <span class="val">{{ $donHang->dia_chi }}</span>
            </div>
            <div class="info-row">
                <span class="lbl">Tổng tiền đơn:</span>
                <span class="val" style="color: #d80f29;">{{ number_format($donHang->tong_tien, 0, ',', '.') }} đ</span>
            </div>
            <div class="info-row">
                <span class="lbl">Phương thức TT:</span>
                <span class="val">{{ $donHang->pt_thanh_toan }}</span>
            </div>
            <div class="info-row">
                <span class="lbl">Trạng thái TT:</span>
                <span class="val">{{ $donHang->trang_thai_thanh_toan }}</span>
            </div>
            @if($donHang->ngay_dat)
            <div class="info-row">
                <span class="lbl">Ngày đặt:</span>
                <span class="val">{{ \Carbon\Carbon::parse($donHang->ngay_dat)->format('d/m/Y H:i') }}</span>
            </div>
            @endif
        </div>

        {{-- Danh sách sản phẩm cần trả --}}
        <div class="info-card">
            <h4><i class="fa-solid fa-basket-shopping"></i> Sản phẩm trong đơn</h4>
            <table class="product-table">
                <thead>
                    <tr>
                        <th style="width:60px;">Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th style="text-align:center;">Số lượng</th>
                        <th style="text-align:right;">Đơn giá</th>
                        <th style="text-align:right;">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chiTiet as $ct)
                    <tr>
                        <td><img src="{{ asset('uploads/' . ($ct->anh_san_pham ?? 'default.jpg')) }}" alt="{{ $ct->ten_san_pham }}"></td>
                        <td><strong>{{ $ct->ten_san_pham }}</strong></td>
                        <td style="text-align:center;">{{ $ct->so_luong }}</td>
                        <td style="text-align:right;">{{ number_format($ct->gia, 0, ',', '.') }}đ</td>
                        <td style="text-align:right; color:#d80f29; font-weight:700;">{{ number_format($ct->gia * $ct->so_luong, 0, ',', '.') }}đ</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Khu vực xử lý --}}
        <div class="action-card">
            <h4><i class="fa-solid fa-sliders"></i> Xử lý yêu cầu</h4>

            <div class="action-grid">
                {{-- DUYỆT TRẢ HÀNG --}}
                <div class="approve-panel">
                    <h5><i class="fa-solid fa-circle-check"></i> Duyệt trả hàng</h5>
                    <p>Xác nhận trả hàng hợp lệ. Hệ thống sẽ <strong>hoàn lại tồn kho</strong> và chuyển đơn sang trạng thái <strong>"Đã trả hàng"</strong>.</p>
                    <form method="POST" action="{{ route('admin.donhang.xuLyTraHang', $donHang->id) }}" onsubmit="return confirm('Xác nhận duyệt trả hàng đơn #{{ $donHang->id }}? Tồn kho sẽ được hoàn lại.')">
                        @csrf
                        <input type="hidden" name="hanh_dong" value="duyet">
                        <button type="submit" class="btn-approve">
                            <i class="fa-solid fa-circle-check"></i> Duyệt – Hoàn kho
                        </button>
                    </form>
                </div>

                {{-- TỪ CHỐI TRẢ HÀNG --}}
                <div class="reject-panel">
                    <h5><i class="fa-solid fa-circle-xmark"></i> Từ chối trả hàng</h5>
                    <p>Nhập lý do từ chối. Đơn sẽ trở về trạng thái <strong>"Đã nhận hàng"</strong> và khách sẽ thấy lý do từ chối.</p>
                    <form method="POST" action="{{ route('admin.donhang.xuLyTraHang', $donHang->id) }}" id="rejectForm">
                        @csrf
                        <input type="hidden" name="hanh_dong" value="tu_choi">
                        <textarea name="ly_do_tu_choi" id="lyDoTuChoi" placeholder="Nhập lý do từ chối yêu cầu trả hàng..." maxlength="500">{{ old('ly_do_tu_choi') }}</textarea>
                        @error('ly_do_tu_choi')
                            <div style="color:#dc2626; font-size:12px; margin-top:4px;">{{ $message }}</div>
                        @enderror
                        <div id="rejectErr" class="err-text">Vui lòng nhập lý do từ chối (ít nhất 5 ký tự).</div>
                        <button type="submit" class="btn-reject" onclick="return validateReject()">
                            <i class="fa-solid fa-circle-xmark"></i> Từ chối yêu cầu
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
<script>
    function validateReject() {
        const val = document.getElementById('lyDoTuChoi').value.trim();
        const err = document.getElementById('rejectErr');
        if (val.length < 5) {
            err.style.display = 'block';
            return false;
        }
        err.style.display = 'none';
        return true;
    }
</script>
@endpush
