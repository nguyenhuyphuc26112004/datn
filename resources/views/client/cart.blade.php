<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <title>Giỏ hàng của bạn</title>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .quanLyDH {
            margin: 20px auto;
            max-width: 1100px;
            min-height: 80vh;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .quanLyDH table {
            font-family: 'Segoe UI', Arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #eeeeee;
            text-align: left;
            padding: 15px;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
            text-transform: uppercase;
            font-size: 14px;
        }

        .quanLyDH table tr:hover {
            background-color: #fcfcfc;
            transition: 0.2s;
        }

        .title-cart {
            margin-bottom: 20px;
            border-bottom: 2px solid #28c361;
            display: inline-block;
        }

        .btn-delete {
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            background-color: #DC3640;
            color: white;
            font-size: 14px;
            transition: 0.3s;
            border: none;
            cursor: pointer;
            display: inline-block;
        }

        .btn-delete:hover {
            opacity: 0.8;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .pay {
            text-decoration: none;
            padding: 12px 25px;
            background-color: #28c361;
            color: #fff;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .pay:hover {
            background-color: #21a350;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .empty-cart-container {
            text-align: center;
            padding: 100px 20px;
            min-height: 60vh;
        }

        .gio-hang-thong-bao {
            color: #999;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .btn-back {
            display: inline-block;
            padding: 12px 25px;
            background-color: #151534;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        .btn-back:hover {
            background-color: #28c361;
        }

        .total-price {
            color: red;
            font-weight: bold;
            font-size: 20px;
        }

        .alert-success {
            padding: 10px 15px;
            background-color: #d4edda;
            color: #155724;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .alert-error {
            padding: 10px 15px;
            background-color: #f8d7da;
            color: #721c24;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #f5c6cb;
        }

        .alert-error ul {
            margin: 0;
            padding-left: 20px;
        }

        .alert-error li {
            margin-bottom: 4px;
        }

        .alert-error li:last-child {
            margin-bottom: 0;
        }
    </style>
</head>

<body>
    {{-- Header --}}
    @include('client.layouts.header')

    <div style="max-width: 1100px; margin: 20px auto 0; padding: 0 15px;">
        @if(session('successCart') || session('success'))
            <div class="alert-success">
                {{ session('successCart') ?? session('success') }}
            </div>
        @endif
        
        @if(session('errorCart') || session('error') || $errors->any())
            <div class="alert-error">
                @if($errors->any())
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                @else
                    @php
                        $errorsList = session('errorCart') ?? session('error');
                    @endphp

                    @if(is_array($errorsList))
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach($errorsList as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    @else
                        <div>{{ $errorsList }}</div>
                    @endif
                @endif
            </div>
        @endif
    </div>

    @if(!$gioHang || count($cTGioHang) == 0)
        <div class="empty-cart-container">
            <i class="fa-solid fa-cart-shopping" style="font-size: 80px; color: #ddd; margin-bottom: 20px;"></i>
            <h1 class="gio-hang-thong-bao">Giỏ hàng của bạn đang trống</h1>
            <a href="{{ route('client.home') }}" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Quay lại cửa hàng</a>
        </div>
    @else
        <div class="quanLyDH">

            <div class="title-cart">
                <h1><i class="fa-solid fa-cart-shopping"></i> Giỏ hàng của bạn</h1>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Ảnh sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th style="text-align: center;">Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @php $tongTien = 0; @endphp
                    @foreach ($cTGioHang as $cTGH)
                        @php
                            $thanhTien = $cTGH->so_luong * $cTGH->gia_san_pham;
                            $tongTien += $thanhTien;
                        @endphp
                        <tr id="row-sp-{{ $cTGH->id_san_pham }}">
                            <td>
                                <img src="{{ asset('uploads/' . $cTGH->anh_san_pham) }}" alt="{{ $cTGH->ten_san_pham }}" width="70px" height="70px" style="object-fit: cover; border-radius: 5px;">
                            </td>
                            <td>
                                <a style="color: #151534; font-weight: 500; text-decoration: none;" href="{{ route('client.product.detail', $cTGH->id_san_pham) }}">
                                    {{ $cTGH->ten_san_pham }}
                                </a>
                            </td>
                            <td style="text-align: center;">
                                {{ $cTGH->so_luong }}
                            </td>
                            <td style="color: #333; font-weight: 600;" id="thanh-tien-{{ $cTGH->id_san_pham }}">
                                {{ number_format($thanhTien, 0, ',', '.') }} VNĐ
                            </td>
                            <td>
                                <form action="{{ route('client.cart.remove') }}" method="POST" style="display: inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                    @csrf
                                    <input type="hidden" name="id_san_pham" value="{{ $cTGH->id_san_pham }}">
                                    <input type="hidden" name="id_gio_hang" value="{{ $gioHang->id }}">
                                    <button type="submit" class="btn-delete">
                                        <i class="fa-solid fa-trash-can"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align: right;">
                            <h3>Tổng đơn hàng:</h3>
                        </td>
                        <td colspan="2" style="text-align: center;">
                            <span class="total-price" id="tong-don-hang">{{ number_format($tongTien, 0, ',', '.') }} VNĐ</span>
                        </td>
                        <td>
                            <a href="{{ route('client.trangDatHang', ['type' => 'cart', 'id' => $gioHang->id]) }}" class="pay">
                                Thanh toán <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif

    {{-- Footer --}}
    @include('client.layouts.footer')
</body>

</html>