<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phiếu Giao Hàng & Hóa Đơn #{{ $donHang->id }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'DejaVu Sans', Arial, sans-serif; }
        body { background: #f4f6f9; padding: 20px; color: #333; }
        .invoice-card { max-width: 800px; margin: 0 auto; background: #fff; padding: 35px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #22c55e; padding-bottom: 15px; margin-bottom: 20px; }
        .shop-info h2 { color: #16a34a; font-size: 24px; font-weight: bold; margin-bottom: 5px; }
        .shop-info p { font-size: 13px; color: #555; line-height: 1.4; }
        .invoice-title { text-align: right; }
        .invoice-title h3 { font-size: 20px; color: #1f2937; text-transform: uppercase; }
        .invoice-title p { font-size: 13px; color: #6b7280; margin-top: 4px; }
        .customer-info { display: flex; justify-content: space-between; margin-bottom: 25px; background: #f9fafb; padding: 15px; border-radius: 6px; border: 1px solid #e5e7eb; }
        .customer-info div { flex: 1; font-size: 13px; line-height: 1.6; }
        .customer-info b { color: #111827; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 13px; }
        th { background: #f3f4f6; color: #374151; font-weight: bold; padding: 10px; border: 1px solid #e5e7eb; text-align: left; }
        td { padding: 10px; border: 1px solid #e5e7eb; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .totals { display: flex; justify-content: flex-end; margin-bottom: 30px; }
        .totals-table { width: 320px; font-size: 13px; }
        .totals-table div { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px dashed #e5e7eb; }
        .totals-table .grand-total { font-size: 16px; font-weight: bold; color: #dc2626; border-top: 2px solid #111827; border-bottom: none; padding-top: 8px; margin-top: 4px; }
        .signatures { display: flex; justify-content: space-between; text-align: center; margin-top: 40px; font-size: 13px; }
        .signatures div { width: 30%; }
        .signatures small { color: #6b7280; font-style: italic; }
        .sign-space { height: 70px; }
        .no-print { text-align: center; margin-bottom: 20px; }
        .btn-print { background: #16a34a; color: #fff; border: none; padding: 10px 24px; border-radius: 6px; font-size: 14px; font-weight: bold; cursor: pointer; }
        .btn-back { background: #6b7280; color: #fff; border: none; padding: 10px 20px; border-radius: 6px; font-size: 14px; text-decoration: none; margin-right: 10px; display: inline-block; }

        @media print {
            body { background: #fff; padding: 0; }
            .invoice-card { box-shadow: none; padding: 0; width: 100%; max-width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <a href="{{ route('admin.donhang.show', $donHang->id) }}" class="btn-back">← Quay lại</a>
        <button onclick="window.print()" class="btn-print">🖨️ In Phiếu Giao Hàng</button>
    </div>

    <div class="invoice-card">
        <!-- HEADER -->
        <div class="header">
            <div class="shop-info">
                <h2>CỬA HÀNG TRÁI CÂY TƯƠI</h2>
                <p>📍 Địa chỉ: 123 Đường Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh</p>
                <p>📞 Hotline: 1800 1502 | Email: support@fruitshop.vn</p>
            </div>
            <div class="invoice-title">
                <h3>PHIẾU ĐÓNG GÓI & GIAO HÀNG</h3>
                <p>Mã đơn hàng: <b>#{{ $donHang->id }}</b></p>
                <p>Ngày đặt: {{ \Carbon\Carbon::parse($donHang->ngay_dat)->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- THÔNG TIN KHÁCH HÀNG & GIAO VẬN -->
        <div class="customer-info">
            <div>
                <p><b>Người nhận:</b> {{ $donHang->ten }}</p>
                <p><b>Số điện thoại:</b> {{ $donHang->sdt }}</p>
                <p><b>Email:</b> {{ $donHang->email }}</p>
                <p><b>Địa chỉ giao:</b> {{ $donHang->dia_chi }}</p>
            </div>
            <div>
                <p><b>Hình thức thanh toán:</b> {{ $donHang->pt_thanh_toan === 'ONLINE' ? 'Chuyển khoản VietQR' : 'Tiền mặt khi nhận hàng (COD)' }}</p>
                <p><b>Trạng thái thanh toán:</b> 
                    <span style="color: {{ $donHang->trang_thai_thanh_toan === 'Đã thanh toán' ? '#16a34a' : '#ea580c' }}; font-weight: bold;">
                        {{ $donHang->trang_thai_thanh_toan }}
                    </span>
                </p>
                <p><b>Trạng thái đơn:</b> {{ $donHang->trang_thai }}</p>
                @if($donHang->ma_chuyen_khoan)
                    <p><b>Mã giao dịch:</b> {{ $donHang->ma_chuyen_khoan }}</p>
                @endif
            </div>
        </div>

        <!-- BẢNG MÓN HÀNG -->
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 40px;">STT</th>
                    <th>Tên Sản Phẩm</th>
                    <th class="text-center" style="width: 70px;">ĐVT</th>
                    <th class="text-center" style="width: 80px;">Số Lượng</th>
                    <th class="text-right" style="width: 110px;">Đơn Giá</th>
                    <th class="text-right" style="width: 130px;">Thành Tiền</th>
                </tr>
            </thead>
            <tbody>
                @php $tienHang = 0; @endphp
                @foreach ($chiTiet as $index => $item)
                    @php 
                        $thanhTien = $item->so_luong * $item->gia;
                        $tienHang += $thanhTien;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td><b>{{ $item->ten_san_pham }}</b></td>
                        <td class="text-center">{{ $item->don_vi ?? 'Kg' }}</td>
                        <td class="text-center">{{ $item->so_luong }}</td>
                        <td class="text-right">{{ number_format($item->gia, 0, ',', '.') }}đ</td>
                        <td class="text-right">{{ number_format($thanhTien, 0, ',', '.') }}đ</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- TỔNG CỘNG -->
        <div class="totals">
            <div class="totals-table">
                <div>
                    <span>Tiền hàng:</span>
                    <span>{{ number_format($tienHang, 0, ',', '.') }}đ</span>
                </div>
                <div>
                    <span>Giảm giá (Voucher {{ $donHang->ma_khuyen_mai ?? '' }}):</span>
                    <span style="color: #dc2626;">-{{ number_format($donHang->tien_giam ?? 0, 0, ',', '.') }}đ</span>
                </div>
                <div>
                    <span>Phí vận chuyển:</span>
                    <span>{{ number_format($donHang->phi_van_chuyen ?? 0, 0, ',', '.') }}đ</span>
                </div>
                <div class="grand-total">
                    <span>Tổng tiền thanh toán:</span>
                    <span>{{ number_format($donHang->tong_tien, 0, ',', '.') }}đ</span>
                </div>
                @if($donHang->pt_thanh_toan === 'COD')
                    <div style="font-weight: bold; color: #b91c1c; font-size: 14px; margin-top: 4px;">
                        <span>Tiền thu hộ COD:</span>
                        <span>{{ number_format($donHang->tong_tien, 0, ',', '.') }}đ</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- CHỮ KÝ -->
        <div class="signatures">
            <div>
                <p><b>Người Lập Phiếu</b></p>
                <small>(Ký, ghi rõ họ tên)</small>
                <div class="sign-space"></div>
            </div>
            <div>
                <p><b>Nhân Viên Giao Hàng</b></p>
                <small>(Ký, ghi rõ họ tên)</small>
                <div class="sign-space"></div>
            </div>
            <div>
                <p><b>Người Nhận Hàng</b></p>
                <small>(Ký, ghi rõ họ tên)</small>
                <div class="sign-space"></div>
            </div>
        </div>
    </div>

</body>
</html>
