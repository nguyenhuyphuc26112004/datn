<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <title>Đồ uống trái cây</title>
</head>
<body class="product-category-page">
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
                <b>Đồ uống trái cây</b>
            </div>
            <div class="product">
                @forelse ($products as $sp)
                    <div class="picture">
                        <a href="{{ route('client.product.detail', $sp->id) }}">
                            <img src="{{ asset('uploads/' . $sp->anh) }}" alt="{{ $sp->ten }}">
                        </a>
                        <div class="name">
                            <a href="{{ route('client.product.detail', $sp->id) }}">{{ $sp->ten }}</a>
                        </div>
                        @if(!empty($sp->don_vi))
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

    @include('client.layouts.footer')
</body>
</html>