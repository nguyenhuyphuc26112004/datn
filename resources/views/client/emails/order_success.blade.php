<div style="font-family: Arial, sans-serif; padding: 20px; border: 1px solid #eee;">
    <h2 style="color: #28a745;">Thông báo đặt hàng thành công!</h2>
    <p>Chào <b>{{ $donHang->ten }}</b>,</p>
    <p>Cảm ơn bạn đã ủng hộ. Đơn hàng <b>#{{ $donHang->id }}</b> của bạn đang được xử lý.</p>
    <hr>
    <p><b>Danh sách sản phẩm:</b></p>
    <ul>
        @foreach($donHang->ctDonHangs as $ct)
            <li>
                {{ $ct->sanPham->ten ?? 'Sản phẩm' }} - 
                SL: {{ $ct->so_luong }} - 
                Giá: {{ number_format($ct->gia) }}đ
            </li>
        @endforeach
    </ul>
    <p><b>Tổng thanh toán:</b> <span style="color:red;">{{ number_format($donHang->tong_tien) }}đ</span></p>
    <p><b>Phương thức:</b> {{ $donHang->pt_thanh_toan }}</p>
    <p><b>Địa chỉ nhận hàng:</b> {{ $donHang->dia_chi }}</p>
    <hr>
    <p style="font-size: 12px; color: #888;">Chúng tôi sẽ sớm liên hệ qua SĐT {{ $donHang->sdt }} để giao hàng.</p>
</div>