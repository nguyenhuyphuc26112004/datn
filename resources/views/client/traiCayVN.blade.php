<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <title>Trái cây Việt Nam</title>
    <style>
        .product {
            font-size: 20px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);    
            justify-content: space-around;
            background: #ffffff;
            border-radius: 16px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.02);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            margin-left: 20px;
            margin-top: 15px;
        }
        
        .product img {
            cursor: pointer;
            width: 180px;
            height: 220px;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, 0.1) 2px 2px 2px 2px;
            object-fit: cover; 
            transition: transform 0.6s ease;
        }
        .picture {
            margin: 10px 20px;
            padding: 20px;
            border: 2px solid #cecece;
            border-radius: 10px;
        }
        .picture:hover { 
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            transform: translateY(-8px);
            border-color: rgba(39, 174, 96, 0.2);
        }
        .picture:hover img {
            transform: scale(1.05);
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
            border-radius: 10px;
            cursor: pointer;
        }
        .btn a {
            font-size: 18px;
            text-decoration: none;
            color: white;    
        }
        /* .card-footer-pagination {
            padding: 16px 24px;
            border-top: 1px solid var(--border-color);
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pagination {
            display: flex;
            list-style: none;
            gap: 6px;
            align-items: center;
        }

        .pagination li a,
        .pagination li span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .pagination li a:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #0f172a;
        }

        .pagination li.active span {
            background: #10b981;
            border-color: #10b981;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(16, 185, 129, 0.35);
        }

        .pagination li.disabled span {
            color: #cbd5e1;
            background: #f8fafc;
            border-color: #f1f5f9;
            cursor: not-allowed;
        } */
    </style>
</head>
<body>
    {{-- Header --}}
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
            <div class="tieude">
                <b>Trái cây Việt Nam</b>
            </div>
            <div class="product">
                @forelse ($products as $sp)
                    <div class="picture">
                        <a href="{{ route('client.product.detail', $sp->id) }}">
                            <img src="{{ asset('uploads/' . $sp->anh) }}" alt="{{ $sp->ten ?? $sp->ten_san_pham }}">
                        </a>
                        <div class="name">
                            <a href="{{ route('client.product.detail', $sp->id) }}">{{ $sp->ten ?? $sp->ten_san_pham }}</a>
                        </div>
                        @if(isset($sp->don_vi))
                            <div class="donVi">
                                Đơn vị: {{ $sp->don_vi }}
                            </div>
                        @endif
                        <div class="price">
                            <p>Giá: {{ number_format($sp->gia, 0, ',', '.') }} VNĐ</p>
                        </div>
                        <div class="btn">
                            <button>
                                <a href="{{ route('client.product.detail', $sp->id) }}">Xem chi tiết</a>
                            </button>
                        </div>
                    </div>
                @empty
                    <p style="grid-column: 1 / -1; margin: 30px 0;">Không tìm thấy sản phẩm nào thuộc danh mục này.</p>
                @endforelse
            </div>

            @if($products->hasPages())
                <div class="card-footer-pagination">
                    {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div> 

    {{-- Footer --}}
    @include('client.layouts.footer')
</body>
</html>