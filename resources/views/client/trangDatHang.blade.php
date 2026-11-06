<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Thanh toán đơn hàng</title>
    <link rel="stylesheet" href="{{ asset('css/trangDatHang.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    
</head>
<body>

    <div class="checkout-wrapper">
        @if(session('error'))
            <div style="max-width: 80%; margin: 0 auto 20px; padding: 15px 20px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 8px;">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div style="max-width: 80%; margin: 0 auto 20px; padding: 15px 20px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 8px;">
                <strong><i class="fa-solid fa-triangle-exclamation"></i> Vui lòng kiểm tra lại:</strong>
                <ul style="margin: 5px 0 0 20px; padding: 0;">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="formXacNhan" action="{{ route('client.xuLyDatHang') }}" method="POST">
            @csrf
            <div class="form-info">
                <div class="info-left">
                    <h3>THÔNG TIN GIAO HÀNG</h3>
                    
                    <div class="info">
                        <label>Họ và tên *</label>
                        <input type="text" id="name" name="name" placeholder="Nhập tên người nhận" value="{{ old('name', $user->ho_ten ?? '') }}">
                        <span class="error" id="nameError">Vui lòng nhập họ tên</span>
                    </div>

                    <div class="info">
                        <label>Số điện thoại *</label>
                        <input type="text" id="sdt" name="sdt" placeholder="Nhập số điện thoại" value="{{ old('sdt', $user->sdt ?? '') }}">
                        <span class="error" id="sdtError">Vui lòng nhập số điện thoại</span>
                    </div>

                    <div class="info">
                        <label>Email *</label>
                        <input type="email" id="email" name="email" placeholder="Nhập email" value="{{ old('email', $user->email ?? '') }}">
                        <span class="error" id="emailError">Vui lòng nhập email hợp lệ</span>
                    </div>

                    <div class="info">
                        <label>Địa chỉ nhận hàng *</label>
                        <input type="text" id="address" name="address" autocomplete="off" placeholder="Nhập số nhà, tên đường, phường/xã, quận/huyện..." value="{{ old('address', $user->dia_chi ?? '') }}" oninput="goiYDiaChi(this.value)">
                        <div id="addressSuggestions" class="address-suggestions"></div>
                        <span class="error" id="addressError">Vui lòng chọn hoặc nhập địa chỉ cụ thể</span>
                    </div>

                    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                    <input type="hidden" id="phi_van_chuyen_input" name="phi_van_chuyen" value="{{ $phiVanChuyen }}">
                    
                    <button type="button" id="btnContinue" class="btn-tiep-tuc" onclick="checkInfoBeforePayment()">TIẾP TỤC CHỌN THANH TOÁN</button>
                </div>

                <div class="info-right">
                    <h3>ĐƠN HÀNG CỦA BẠN</h3>

                    @php $tongTien = 0; @endphp

                    @foreach ($cTGioHang as $cTGH)
                        @php 
                            $thanhTien = $cTGH['so_luong'] * $cTGH['gia'];
                            $tongTien += $thanhTien;
                        @endphp
                        <div class="cart-info">
                            <div style="display:flex; align-items:center; gap:10px;">
                                <img src="{{ asset('uploads/' . $cTGH['anh']) }}" style="width:50px; height:50px; object-fit:cover;">
                                <div>
                                    <b>{{ $cTGH['ten'] }}</b><br>
                                    <small>x{{ $cTGH['so_luong'] }}</small>
                                </div>
                            </div>
                            <span>{{ number_format($thanhTien, 0, ',', '.') }}đ</span>
                        </div>
                    @endforeach

                    @php 
                        $tongTienHang = $tongTien; 
                        $tongThanhToanBanDau = $tongTienHang + $phiVanChuyen;
                        $bankID = "MB";
                        $accountNo = "0368334112";
                        $accountName = urlencode("NGUYEN HUY PHUC");
                    @endphp

                    <div style="margin-top: 15px; padding: 12px; background: #fff; border-radius: 6px; border: 1px solid #e5e7eb; font-size: 14px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                            <span>Tiền hàng:</span>
                            <span id="tienHangBanDau">{{ number_format($tongTienHang, 0, ',', '.') }}đ</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                            <span>Phí vận chuyển:</span>
                            <span id="hienThiPhiShip" style="color: {{ $phiVanChuyen == 0 ? '#16a34a' : '#374151' }}; font-weight: {{ $phiVanChuyen == 0 ? 'bold' : 'normal' }};">
                                {{ $phiVanChuyen == 0 ? 'Miễn phí giao hàng (Freeship)' : number_format($phiVanChuyen, 0, ',', '.') . 'đ' }}
                            </span>
                        </div>
                        @if($tongTienHang < 300000)
                            <small style="color: #ea580c; display: block; margin-top: 2px;">
                                💡 Mua thêm {{ number_format(300000 - $tongTienHang, 0, ',', '.') }}đ để được <b>Freeship toàn quốc</b>!
                            </small>
                        @endif
                    </div>

                    <div class="tongCong" id="tongGoc">Tổng thanh toán: <span id="hienThiTongThanhToan">{{ number_format($tongThanhToanBanDau, 0, ',', '.') }}đ</span></div>

                    <div style="margin-top: 16px; padding: 14px 16px; border: 1px dashed #d1d5db; border-radius: 8px; background: #fafafa;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin: 0 0 10px;">
                            <p style="font-size: 13px; font-weight: 600; color: #374151; margin: 0;">
                                <i class="fa-solid fa-ticket-simple" style="color: #6366f1;"></i> Mã giảm giá
                            </p>
                            <button type="button" onclick="moPopupVoucher()"
                                    style="font-size: 12px; color: #6366f1; background: none; border: none; cursor: pointer; text-decoration: underline; padding: 0; font-weight: 600;">
                                <i class="fa-solid fa-tags"></i> Xem mã đang có
                            </button>
                        </div>
                        
                        <div style="display: flex; gap: 8px;">
                            <input type="text" id="ma_voucher_input" placeholder="Nhập hoặc chọn mã..."
                                   style="flex: 1; padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; outline: none;"
                                   oninput="this.value = this.value.toUpperCase()">
                            <button type="button" id="btnApDungVoucher" onclick="apDungVoucher()"
                                    style="padding: 9px 16px; background: #6366f1; color: #fff; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap;">
                                Áp dụng
                            </button>
                            <button type="button" id="btnXoaVoucher" onclick="xoaVoucher()"
                                    style="display:none; padding: 9px 10px; background: #fee2e2; color: #dc2626; border: none; border-radius: 6px; font-size: 13px; cursor: pointer;">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        <p id="voucherMsg" style="font-size: 13px; margin: 8px 0 0; color: #059669; display: none;"></p>
                    </div>

                    <!-- Hiển thị tổng sau giảm giá -->
                    <div id="chiTietGia" style="display:none; margin-top: 12px; padding: 10px 14px; background: #f0fdf4; border-radius: 8px; border: 1px solid #bbf7d0;">
                        <div style="display:flex; justify-content:space-between; font-size:13px; color:#374151; margin-bottom:6px;">
                            <span>Tổng tiền hàng:</span>
                            <span id="displayTongGoc"></span>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:13px; color:#dc2626; margin-bottom:6px;">
                            <span>Giảm giá:</span>
                            <span id="displayTienGiam"></span>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:13px; color:#374151; margin-bottom:6px;">
                            <span>Phí vận chuyển:</span>
                            <span id="displayPhiShip">{{ $phiVanChuyen == 0 ? '0đ (Freeship)' : number_format($phiVanChuyen, 0, ',', '.') . 'đ' }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:16px; font-weight:700; color:#059669;">
                            <span>Thanh toán:</span>
                            <span id="displayTongSauGiam"></span>
                        </div>
                    </div>

                    <input type="hidden" id="ma_khuyen_mai" name="ma_khuyen_mai" value="">
                    <input type="hidden" id="tien_giam" name="tien_giam" value="0">
                    <input type="hidden" id="inputTongTien" name="tongTien" value="{{ $tongThanhToanBanDau }}">

                    <!-- PHẦN THANH TOÁN -->
                    <div id="payment_section">
                        <div class="payment-box" style="margin-top:20px; border:1px solid #eee; padding:15px; border-radius:8px;">
                            <b>Phương thức thanh toán *</b><br><br>
                            <label><input type="radio" name="payment_method" value="COD" checked onclick="toggleQR(false)"> Tiền mặt khi nhận hàng (COD)</label><br><br>
                            <label><input type="radio" name="payment_method" value="ONLINE" onclick="toggleQR(true)"> Chuyển khoản VietQR</label>
                            
                            @php 
                                $qrUrlBanDau = "https://img.vietqr.io/image/{$bankID}-{$accountNo}-compact2.png?amount={$tongThanhToanBanDau}&addInfo={$maChuyenKhoan}&accountName={$accountName}";
                            @endphp

                            <div id="qr_code_area">
                                <p style="font-size:13px; color:#555;">Quét mã bằng App Ngân hàng:</p>
                                <img id="imgQrCode" src="{{ $qrUrlBanDau }}" style="width:100%; max-width:200px; border: 2px solid #fff;">
                                <p style="margin-top:10px;">Nội dung: <b style="color:blue;">{{ $maChuyenKhoan }}</b></p>
                                <div style="margin-top: 8px; padding: 8px; background: #fff3cd; border: 1px solid #ffeeba; border-radius: 4px; font-size: 12px; color: #856404;">
                                    ⏱️ <b>Thời hạn thanh toán:</b> 30 phút kể từ lúc đặt hàng. Quá thời gian trên, hệ thống sẽ tự động hủy đơn để hoàn lại tồn kho.
                                </div>
                            </div>
                        </div>

                        <div style="margin-top:20px;">
                            <label style="font-size:14px;"><input type="checkbox" id="terms"> Tôi đồng ý với điều khoản dịch vụ và chính sách kiểm hàng</label>
                            <span class="error" id="termsError" >Vui lòng đồng ý với điều khoản</span>
                        </div>

                        <input type="hidden" name="ma_chuyen_khoan" value="{{ $maChuyenKhoan }}">
                        <input type="hidden" name="checkout_type" value="{{ $type }}">
                        
                        <button type="submit" name="btn_dat_hang" class="btn-dat-hang">XÁC NHẬN ĐẶT HÀNG</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

<!-- POPUP VOUCHER -->
<div id="popupVoucherOverlay" onclick="dongPopupVoucher()" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9998;"></div>
<div id="popupVoucher" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); width:min(480px, 95vw); max-height:80vh; background:#fff; border-radius:16px; box-shadow:0 20px 60px rgba(0,0,0,0.2); z-index:9999; overflow:hidden; flex-direction:column;">
    <div style="padding:18px 20px 14px; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center; background: linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff;">
        <div>
            <h3 style="margin:0; font-size:16px; font-weight:700;"><i class="fa-solid fa-tags"></i> Mã giảm giá hiện có</h3>
            <p style="margin:4px 0 0; font-size:12px; opacity:0.85;">Nhấn vào mã để áp dụng ngay</p>
        </div>
        <button onclick="dongPopupVoucher()" style="background:rgba(255,255,255,0.2); border:none; color:#fff; width:32px; height:32px; border-radius:50%; cursor:pointer; font-size:16px; display:flex; align-items:center; justify-content:center;">&times;</button>
    </div>
    <!-- Body -->
    <div id="popupVoucherBody" style="overflow-y:auto; max-height:calc(80vh - 80px); padding:16px;">
        <div id="popupVoucherLoading" style="text-align:center; padding:30px; color:#6b7280;">
            <i class="fa-solid fa-spinner fa-spin" style="font-size:24px; margin-bottom:10px;"></i>
            <p style="margin:0; font-size:14px;">Đang tải danh sách mã...</p>
        </div>
        <div id="popupVoucherList" style="display:none;"></div>
        <div id="popupVoucherEmpty" style="display:none; text-align:center; padding:30px; color:#9ca3af;">
            <i class="fa-solid fa-ticket-simple" style="font-size:32px; margin-bottom:12px; display:block;"></i>
            <p style="margin:0; font-size:14px;">Hiện chưa có mã khuyến mãi nào đang hoạt động.</p>
        </div>
    </div>
</div>

<!-- KHAI BÁO BIẾN TOÀN CỤC VÀ NHÚNG FILE JS ĐÃ TÁCH -->
<script>
    window.TONG_TIEN_HANG = {{ $tongTienHang }};
    window.currentPhiShip = {{ $phiVanChuyen }};

    window.SHOP_LAT = 21.04753; 
    window.SHOP_LNG = 105.77797;

    window.BANK_ID = "{{ $bankID }}";
    window.ACCOUNT_NO = "{{ $accountNo }}";
    window.ACCOUNT_NAME = "{{ $accountName }}";
    window.MA_CHUYEN_KHOAN = "{{ $maChuyenKhoan }}";

    window.voucherListUrl = "{{ route('client.voucher.danhSach') }}";
    window.voucherApplyUrl = "{{ route('client.voucher.apDung') }}";
</script>

<script src="{{ asset('js/trangDatHang.js') }}"></script>

@if($errors->any() || old('name'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentSec = document.getElementById('payment_section');
        const btnCont = document.getElementById('btnContinue');
        if (paymentSec) paymentSec.style.display = "block";
        if (btnCont) btnCont.style.display = "none";
    });
</script>
@endif

</body>
</html>