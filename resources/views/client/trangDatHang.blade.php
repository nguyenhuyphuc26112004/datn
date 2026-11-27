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
        <!-- Thông báo lỗi hệ thống chung (nếu có ngoại lệ DB/Transaction) -->
        @if(session('error'))
            <div style="max-width: 80%; margin: 0 auto 20px; padding: 15px 20px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 8px;">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        <form id="formXacNhan" action="{{ route('client.xuLyDatHang') }}" method="POST">
            @csrf
            <div class="form-info">
                <div class="info-left">
                    <h3>THÔNG TIN GIAO HÀNG</h3>
                    
                    <div class="info">
                        <label>Họ và tên *</label>
                        <input type="text" id="name" name="name" placeholder="Nhập tên người nhận" 
                            value="{{ old('name', $user->ho_ten ?? '') }}" 
                            style="{{ $errors->has('name') ? 'border-color: red;' : '' }}">
                            
                        <span class="error" id="nameError" style="{{ $errors->has('name') ? 'display: block;' : '' }}">
                            {{ $errors->first('name') }}
                        </span>
                    </div>

                    <div class="info">
                        <label>Số điện thoại *</label>
                        <input type="text" id="sdt" name="sdt" placeholder="Nhập số điện thoại" 
                            value="{{ old('sdt', '') }}"
                            style="{{ $errors->has('sdt') ? 'border-color: red;' : '' }}">
                            
                        <span class="error" id="sdtError" style="{{ $errors->has('sdt') ? 'display: block;' : '' }}">
                            {{ $errors->first('sdt') }}
                        </span>
                    </div>

                    <div class="info">
                        <label>Email *</label>
                        <input type="email" id="email" name="email" placeholder="Nhập email" 
                            value="{{ old('email', $user->email ?? '') }}"
                            style="{{ $errors->has('email') ? 'border-color: red;' : '' }}">
                            
                        <span class="error" id="emailError" style="{{ $errors->has('email') ? 'display: block;' : '' }}">
                            {{ $errors->first('email') }}
                        </span>
                    </div>

                    <!-- Địa chỉ -->
                    <div class="info">
                        <label>Địa chỉ nhận hàng *</label>
                        <input type="text" id="address" name="address" autocomplete="off" 
                            placeholder="Nhập địa chỉ..." 
                            value="{{ old('address', '') }}" oninput="goiYDiaChi(this.value)"
                            style="{{ $errors->has('address') ? 'border-color: red;' : '' }}">
                        <div id="addressSuggestions" class="address-suggestions"></div>
                            
                        <span class="error" id="addressError" style="{{ $errors->has('address') ? 'display: block;' : '' }}">
                            {{ $errors->first('address') }}
                        </span>
                    </div>

                    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                    <input type="hidden" id="phi_van_chuyen" name="phi_van_chuyen" value="{{ $phiVanChuyen }}">
                    
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
                        $diaChiCheckout = trim(old('address', ''));
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
                            <span id="hienThiPhiShip">{{ $diaChiCheckout === '' ? 'Nhập địa chỉ để tính phí' : '' }}</span>
                        </div>
                        @if($tongTienHang < 300000)
                            <small style="color: #ea580c; display: block; margin-top: 2px;">
                                💡 Mua thêm {{ number_format(300000 - $tongTienHang, 0, ',', '.') }}đ để được <b>Freeship toàn quốc</b>!
                            </small>
                        @endif
                    </div>

                    <div class="tongCong" id="tongGoc">
                        Tổng thanh toán: 
                        <span id="hienThiTongThanhToan">
                            {{ number_format($tongThanhToanBanDau, 0, ',', '.') }}đ
                        </span>
                    </div>
                    
                    <!-- Phần Mã giảm giá -->
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
                                   style="flex: 1; padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; text-transform: uppercase; outline: none;"
                                   oninput="this.value = this.value.toUpperCase()">
                            <button type="button" id="btnApDungVoucher" onclick="apDungVoucher()"
                                    style="padding: 9px 16px; background: #6366f1; color: #fff; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">
                                Áp dụng
                            </button>
                            <button type="button" id="btnXoaVoucher" onclick="xoaVoucher()"
                                    style="display:none; padding: 9px 10px; background: #fee2e2; color: #dc2626; border: none; border-radius: 6px; cursor: pointer;">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        <p id="voucherMsg" style="font-size: 13px; margin: 8px 0 0; color: #059669; display: none;"></p>
                    </div>

                    <div id="chiTietGiam" style="display:none; margin-top: 12px; padding: 10px 14px; background: #f0fdf4; border-radius: 8px; border: 1px solid #bbf7d0;">
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
                            <span id="displayPhiShip">{{ $diaChiCheckout === '' ? 'Nhập địa chỉ để tính phí' : ($phiVanChuyen == 0 ? '0đ (Freeship)' : number_format($phiVanChuyen, 0, ',', '.') . 'đ') }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:16px; font-weight:700; color:#059669;">
                            <span>Thanh toán:</span>
                            <span id="displayTongSauGiam"></span>
                        </div>
                    </div>

                    <input type="hidden" id="ma_khuyen_mai" name="ma_khuyen_mai" value="">
                    <input type="hidden" id="tien_giam" name="tien_giam" value="0">
                    <input type="hidden" id="tong_tien" name="tong_tien" value="{{ $tongThanhToanBanDau }}">

                    <!-- PHẦN THANH TOÁN (Mặc định ẩn, chỉ hiện khi bấm Tiếp tục hoặc Server trả về lỗi) -->
                    <div id="payment_section">
                        <div class="payment-box" style="margin-top:20px; border:1px solid #eee; padding:15px; border-radius:8px;">
                            <b>Phương thức thanh toán *</b><br><br>
                            <label><input type="radio" name="payment_method" value="COD" checked onclick="toggleQR(false)"> Tiền mặt khi nhận hàng (COD)</label><br><br>
                            <label><input type="radio" name="payment_method" value="ONLINE" onclick="toggleQR(true)"> Chuyển khoản VietQR</label>
                            
                            @php 
                                $qrUrlBanDau = "https://img.vietqr.io/image/{$bankID}-{$accountNo}-compact2.png?amount={$tongThanhToanBanDau}&addInfo={$maChuyenKhoan}&accountName={$accountName}";
                            @endphp

                            <div id="qr_code_area" style="display: none; margin-top: 15px; text-align: center;">
                                <p style="font-size:13px; color:#555;">Quét mã bằng App Ngân hàng:</p>
                                <img id="imgQrCode" src="" style="width:100%; max-width:200px; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                
                                <div style="margin: 12px 0; padding: 10px; background: #fee2e2; border: 1px solid #fca5a5; border-radius: 6px; font-size: 13px; color: #991b1b; text-align: left; line-height: 1.4;">
                                    <i class="fa-solid fa-triangle-exclamation" style="margin-right: 5px;"></i>
                                    <b>Lưu ý quan trọng:</b> Mã QR hiển thị để bạn xem trước thông tin. Vui lòng bấm nút <b>"XÁC NHẬN ĐẶT HÀNG"</b> ở phía dưới để tạo đơn hàng trước, sau đó hệ thống chuyển sang trang chi tiết để bạn tiến hành quét mã chuyển khoản!
                                </div>

                                <p style="margin-top:10px;">Nội dung: <b style="color:blue;">{{ $maChuyenKhoan }}</b></p>
                                <div style="margin-top: 8px; padding: 8px; background: #fff3cd; border: 1px solid #ffeeba; border-radius: 4px; font-size: 12px; color: #856404;">
                                    ⏱️ <b>Thời hạn thanh toán:</b> 30 phút kể từ lúc đặt hàng.
                                </div>
                            </div>
                        </div>

                        <div style="margin-top:20px;">
                            <label style="font-size:14px;">
                                <input type="checkbox" id="check" name="check_dieu_khoan" value="1" {{ old('check_dieu_khoan') ? 'checked' : '' }}> 
                                Tôi đồng ý với điều khoản dịch vụ và chính sách kiểm hàng
                            </label>
                            <span class="error" id="checkError" style="display:none;">Vui lòng đồng ý với điều khoản</span>
                        </div>

                        <input type="hidden" name="ma_chuyen_khoan" value="{{ $maChuyenKhoan }}">
                        <input type="hidden" name="checkout_type" value="{{ $type }}">
                        
                        <button type="submit" name="btn_dat_hang" class="btn-dat-hang">XÁC NHẬN ĐẶT HÀNG</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div id="popupVoucherOverlay" class="voucher-overlay"
         onclick="if (event.target === this) dongPopupVoucher()"></div>
    <section id="popupVoucher" class="voucher-popup" role="dialog" aria-modal="true"
             aria-labelledby="popupVoucherTitle">
        <div class="voucher-popup-header">
            <div>
                <h3 id="popupVoucherTitle">Mã giảm giá</h3>
                <p>Chọn mã phù hợp cho đơn hàng của bạn</p>
            </div>
            <button type="button" class="voucher-close-btn" onclick="dongPopupVoucher()"
                    aria-label="Đóng danh sách voucher">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="voucher-popup-body">
            <div id="popupVoucherLoading" class="voucher-loading">Đang tải danh sách mã...</div>
            <div id="popupVoucherList" style="display:none;"></div>
            <div id="popupVoucherEmpty" class="voucher-empty">Hiện chưa có mã giảm giá khả dụng.</div>
        </div>
    </section>

    <template id="templateVoucher">
        <div class="voucher-card">
            <div class="voucher-left-col">
                <div class="voucher-value"></div>
            </div>
            <div class="voucher-content-col">
                <div class="voucher-code-row">
                    <span class="voucher-code"></span>
                    <span class="voucher-badge-used">✓ Đã dùng</span>
                    <span class="voucher-badge-alert"></span>
                </div>
                <p class="voucher-desc"></p>
                <div class="voucher-meta-row">
                    <span class="voucher-meta-item voucher-min-order"></span>
                    <span class="voucher-meta-item voucher-expiry"></span>
                    <span class="voucher-meta-item voucher-remaining"></span>
                </div>
            </div>
            <div class="voucher-action-col">
                <span class="voucher-action"></span>
            </div>
        </div>
    </template>

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


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const oldPaymentMethod = "{{ old('payment_method', 'COD') }}";
            
            if (oldPaymentMethod === 'ONLINE') {
                const onlineRadio = document.querySelector('input[name="payment_method"][value="ONLINE"]');
                if (onlineRadio) {
                    onlineRadio.checked = true;
                }
                
                const qrArea = document.getElementById('qr_code_area');
                if (qrArea) {
                    qrArea.style.display = "block";
                    
                    let tongTienText = document.getElementById('displayTongSauGiam') 
                                       ? document.getElementById('displayTongSauGiam').innerText 
                                       : document.getElementById('hienThiTongThanhToan').innerText;
                    let soTien = parseInt(tongTienText.replace(/\D/g, '')) || window.TONG_TIEN_HANG;
                    if (typeof capNhatQrCode === 'function') {
                        capNhatQrCode(soTien);
                    }
                }
            }
        });
    </script>

</body>
</html>