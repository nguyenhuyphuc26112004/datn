<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <title>Kết quả tìm kiếm</title>
    <style>
        .product {
            font-size: 20px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);    
            justify-content: space-around;
        }
        .product img {
            cursor: pointer;
            width: 180px;
            height: 220px;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, 0.1) 2px 2px 2px 2px;
        }
        .picture {
            margin: 20px 20px 10px;
            padding: 20px;
            border: 2px solid  #cecece;
            border-radius: 10px;
        }
        .tieude {
            font-size: 28px;
        }
        .name {
            margin: 10px 0;
        }
        .name a {
            font-size: 20px;
            text-decoration: none;
            color: black;    
        }
        .price p {
            color: red;
            margin: 10px 0;
        }
        .btn {
            margin-top: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .product button {
            background-color: #28c361;
            padding: 8px 35px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn a {
            font-size: 18px;
            text-decoration: none;
            color: white;    
        }
    </style>
</head>
<body>
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
        <div class="right-container">
            <div class="product">
                @if(isset($products) && count($products) > 0)
                    @foreach ($products as $sp)
                        <div class="picture">
                            <a href="{{ route('client.product.detail', $sp->id) }}">
                                <img src="{{ asset('uploads/' . $sp->anh) }}" alt="Lỗi {{ $sp->anh }}">
                            </a>
                            <div class="name">
                                <a href="{{ route('client.product.detail', $sp->id) }}">{{ $sp->ten }}</a>
                            </div>
                            <div class="donVi">
                                Đơn vị: {{ $sp->don_vi ?? 'Kg' }}
                            </div>
                            <div class="price">
                                <p>Giá: {{ number_format($sp->gia, 0, ',', '.') }} VNĐ</p>
                            </div>
                            <div class="btn">
                                <button>
                                    <a href="{{ route('client.product.detail', $sp->id) }}">Xem chi tiết</a>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p style="padding: 20px;">Không tìm thấy sản phẩm nào khớp với từ khóa.</p>
                @endif
            </div>

            @if(method_exists($products, 'links'))
                <div style="margin-top: 20px;">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</body>
</html>