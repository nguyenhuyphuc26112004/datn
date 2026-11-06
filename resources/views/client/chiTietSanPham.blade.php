<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <title>{{ $product->ten ?? 'Chi tiết sản phẩm' }}</title>
    <style>
        body{
            background-color: #eef0f4;
        }
        .right {
            flex: 1;
        }
        .product{
            display: flex;
            margin: 15px 200px 50px 200px;
            justify-content: center;
            padding: 20px;
            border: 2px solid rgb(210, 202, 202);
            border-radius: 10px;
            background: white;
        }
        .image-product{
            margin-right: 20px;
        }
        .product img{
            width: 280px;
            height: 350px;
            border-radius: 10px;
            object-fit: cover;
        }
        .info-product{
            padding-top: 10px;
            margin-left: 20px;
            font-size: 30px;
            flex: 1;
        }
        .info-product p{
            margin: 10px 0;
        }
        .price{
            display: flex;
            align-items: center;
            margin: 10px 0;
            color: red;
            font-size: 24px;
            font-weight: bold;
        }
        .soLuong{
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .buy-amount {
            display: inline-flex;
            align-items: center;
            margin-left: 30px;
        }

        /* Thống nhất chiều cao và box-sizing cho cả 3 phần */
        .buy-amount button,
        .buy-amount input[type="number"] {
            height: 35px;
            box-sizing: border-box;
            outline: none;
            margin: 0;
            padding: 0;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        /* Nút trừ: bo góc trái, bỏ viền phải */
        .buy-amount .minus-btn {
            width: 35px;
            background: #f8f9fa;
            border-top-left-radius: 6px;
            border-bottom-left-radius: 6px;
            border-right: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Ô nhập số lượng ở giữa: nền trắng, bỏ viền trái/phải để dính liền khối */
        .buy-amount input[type="number"] {
            width: 45px;
            background: #fff;
            text-align: center;
            color: #333;
            border-left: none;
            border-right: none;
            border-radius: 0;
        }

        /* Nút cộng: bo góc phải, bỏ viền trái */
        .buy-amount .plus-btn {
            width: 35px;
            background: #f8f9fa;
            border-top-right-radius: 6px;
            border-bottom-right-radius: 6px;
            border-left: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Hiệu ứng hover */
        .buy-amount button:hover {
            background: #e9ecef;
        }

        .buy-amount button i {
            color: #909090;
        }

        .buy-amount button:hover i {
            color: #4f4f4f;
        }

        /* Ẩn mũi tên mặc định của input number */
        .buy-amount input::-webkit-outer-spin-button,
        .buy-amount input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .buy-amount input[type=number] {
            -moz-appearance: textfield;
        }
        
        .info-product .btn{
            display: flex;
            align-items: center;
        }
        .info-product .btn button{
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }
        .themGioHang button{
            color: #151534;
            background: none;
            padding: 10px 15px;
            font-size: 16px;
            border: 1px solid #d9d9d9;
        }
        .themGioHang button:hover{
            color: #28c361;
            border-color: #28c361;
        }
        .datHang button{
            background-color: #28c361;
            padding: 10px 20px;
            border: none;
            font-size: 16px;
            color: white;    
        }
        .datHang button:hover {
            background-color: #24af55;
        }

        /* CSS MỚI CHO PHẦN MÔ TẢ SẢN PHẨM */
        .product-description {
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
        }
        .product-description h4 {
            font-size: 18px;
            color: #334155;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .product-description .desc-content {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
            background: #f8fafc;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .back-link {
            margin: 20px 200px 10px 200px;
        }
        .back-link a {
            text-decoration: none;
            color: #2c3e50;
            font-size: 18px;
            font-weight: bold;
            transition: color 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .back-link a:hover {
            color: #28c361;
        }
        .alert-error {
            margin: 10px 200px 15px 200px;
            padding: 12px 16px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 6px;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body>
    {{-- Nhúng header từ thư mục client/layouts --}}
    @include('client.layouts.header')

    <div class="container">
        <div class="left-container">
            <div class="list-product">
                <div class="item"><a href="{{ route('client.category.vietnam') }}">Trái cây Việt Nam</a></div>
                <div class="item"><a href="{{ route('client.category.imported') }}">Trái cây nhập khẩu</a></div>
                <div class="item"><a href="{{ route('client.category.dry') }}">Quả sấy khô</a></div>
                <div class="item"><a href="{{ route('client.category.basket') }}">Giỏ trái cây</a></div>                    
                <div class="item"><a href="{{ route('client.category.drink') }}">Đồ uống trái cây</a></div>
            </div>
            <div class="bannerDoc">
                <img src="{{ asset('images/banner.png') }}" alt="Banner">
            </div>
        </div>
        <div class="right">
            <div class="back-link">
                <a href="javascript:void(0);" onclick="{{ session('error') ? 'window.history.go(-2);' : 'window.history.back();' }}">
                    <i class="fa-solid fa-arrow-left"></i> Quay lại
                </a>
            </div>

            @if(session('error'))
                <div class="alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                </div>
            @endif

            <div class="product">
                <div class="image-product">
                    <img src="{{ asset('uploads/' . $product->anh) }}" alt="Lỗi {{ $product->anh }}">
                </div>
                <div class="info-product">
                    <div class="name-product">
                        <p style="font-size: 26px; font-weight: bold; color: #0f172a; margin: 0;">{{ $product->ten }}</p>
                    </div>
                    <div class="price">
                        <p>Giá: {{ number_format($product->gia, 0, ',', '.') }} VNĐ</p>
                    </div>

                    {{-- Form gửi dữ liệu sang CartController qua Route 'client.cart.add' --}}
                    <form action="{{ route('client.cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="idSanPham" value="{{ $product->id }}">
                        <input type="hidden" name="giaSanPham" value="{{ $product->gia }}">
                        
                        <div class="soLuong">
                            <p style="font-size: 16px; color: #334155; margin: 0;">Số lượng: </p>
                            <div class="buy-amount">
                                <button type="button" class="minus-btn" onclick="giam()"><i class="fa-solid fa-minus"></i></button>
                                <input type="number" id="amount" name="soLuong" value="1" min="1">
                                <button type="button" class="plus-btn" onclick="tang()"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>

                        <div class="btn">
                            <div class="themGioHang">
                                <!-- Thêm vào giỏ hàng -->
                                <button type="submit" name="action" value="add">
                                    <i class="fa-solid fa-cart-shopping"></i> Thêm vào giỏ hàng
                                </button>
                            </div>
                            <div class="datHang">
                                <!-- Mua ngay / Thanh toán -->
                                <button type="submit" name="action" value="buy">
                                    Thanh toán
                                </button>
                            </div>
                        </div> 
                    </form>

                    <div class="product-description">
                        <h4>Mô tả sản phẩm</h4>
                        <div class="desc-content">
                            {!! nl2br($product->mo_ta ?? 'Đang cập nhật thông tin mô tả chi tiết cho sản phẩm này.') !!}                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    <script>
        var amountElement = document.getElementById('amount');
        var amount = parseInt(amountElement.value);
        function render(amount) {
            amountElement.value = amount;
        }
        function tang() {
            amount++;
            render(amount);
        }
        function giam() {
            if (amount > 1) {
                amount--;
            }
            render(amount);
        }
    </script>
</body>
</html>