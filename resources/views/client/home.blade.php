<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <title>Trang chủ - Hệ thống Trái Cây</title>
    <style>
        .product-section { 
            max-width: 1250px; 
            margin: 50px auto; 
            padding: 0 15px;
            clear: both; 
        }
        
        .product-title { 
            text-align: left; 
            margin-bottom: 35px; 
            font-size: 26px; 
            color: #2c3e50;
            text-transform: uppercase;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .product-title::before {
            content: '';
            display: inline-block;
            width: 6px;
            height: 30px;
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            border-radius: 10px;
        }

        .product-grid { 
            display: grid; 
            grid-template-columns: repeat(4, 1fr); 
            gap: 25px; 
        }

        .product-item {
            background: #ffffff;
            border-radius: 16px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.02);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product-item:hover { 
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            transform: translateY(-8px);
            border-color: rgba(39, 174, 96, 0.2);
        }

        .product-img-wrapper {
            width: 100%;
            height: 220px;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 15px;
            background: #f8f9fa;
        }

        .product-item img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            transition: transform 0.6s ease;
        }

        .product-item:hover img {
            transform: scale(1.1);
        }

        .product-name { 
            font-weight: 600; 
            margin: 5px 0 10px 0; 
            font-size: 16px;
            color: #2d3436;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-price { 
            color: #e15f41; 
            font-weight: 700; 
            font-size: 20px;
            margin-bottom: 5px;
            background: linear-gradient(to right, #e15f41, #c44569);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .product-unit {
            font-size: 13px;
            color: #b2bec3;
            margin-bottom: 15px;
            font-weight: 400;
        }

        .btn-view { 
            display: inline-block; 
            padding: 10px 20px;
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white; 
            text-decoration: none; 
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            width: 85%;
            margin: 0 auto;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
        }

        .btn-view:hover {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.5);
            transform: translateY(-2px);
        }
        
        .pagination {
            margin-top: 40px;
            display: flex;
            justify-content: center;
            gap: 12px;
            font-family: 'Inter', sans-serif;
        }
        
        .pagination a {
            color: #636e72;
            padding: 10px 18px;
            text-decoration: none;
            background: #fff;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .pagination a.active {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.4);
        }
        
        .pagination a:hover:not(.active) {
            background: #f1f2f6;
            color: #27ae60;
            transform: translateY(-2px);
        }

        /* 2 banner nhỏ phía dưới banner lớn */
        .sub-banner-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .sub-banner-item {
            display: block;
            height: 190px;
            border-radius: 16px;
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .sub-banner-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .sub-banner-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 55%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding-left: 25px;
        }

        .sub-banner-content h2 {
            font-size: 20px;
            font-weight: 800;
            line-height: 1.25;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .sub-banner-item.imported h2 {
            color: #1b5e20; 
        }

        .sub-banner-item.domestic h2 {
            color: #d35400; 
        }

        .sub-banner-content span {
            font-size: 18px;
            font-weight: 600;
            color: #1b5e20;
        }

        .sub-banner-item:hover span {
            color: #000;
        }

        /* ĐÃ ĐẨY LỀ 2 BÊN RIÊNG CHO THANH NÀY (Ví dụ chỉnh 100px, bạn có thể tăng giảm tùy ý) */
        .features-bar {
            margin: 40px 100px 0 100px;
            background: #f1f8f2;
            border-radius: 16px;
            padding: 22px 30px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .feature-icon {
            font-size: 28px;
            color: #27ae60;
            flex-shrink: 0;
        }

        .feature-text h4 {
            font-size: 15px;
            font-weight: 700;
            color: #2d3436;
            margin: 0 0 4px 0;
        }

        .feature-text p {
            font-size: 13px;
            color: #b2bec3;
            margin: 0;
            font-weight: 500;
        }

        @media (max-width: 1024px) {
            .product-grid { grid-template-columns: repeat(3, 1fr); gap: 20px;}
            .features-bar { grid-template-columns: repeat(2, 1fr); gap: 20px; margin: 40px 20px 0 20px; }
        }
        @media (max-width: 768px) {
            .product-grid { grid-template-columns: repeat(2, 1fr); }
            .sub-banner-container { grid-template-columns: 1fr; }
            .features-bar { grid-template-columns: 1fr; }
        }
        @media (max-width: 480px) {
            .product-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    @include('client.layouts.header')

    <div class="mid">
        <div class="left-mid">
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
        <div class="right-mid">
            @if(session('error'))
                <div style="color: red; margin-bottom: 20px; text-align: center; font-weight: bold; background: #ffe6e6; padding: 10px; border-radius: 4px;">
                    {{ session('error') }}
                </div>  
            @endif
                
            <div class="sub-banner-container">
                <a href="{{ route('client.category.imported') }}" class="sub-banner-item imported" style="background-image: url('{{ asset('images/banner_trai-cay-nhap-khau.jpg') }}');">
                    <div class="sub-banner-content">
                        <h2>Trái cây<br>nhập khẩu</h2>
                        <span>Xem ngay &rsaquo;</span>
                    </div>
                </a>

                <a href="{{ route('client.category.vietnam') }}" class="sub-banner-item domestic" style="background-image: url('{{ asset('images/banner_trai-cay-noi-dia.jpg') }}');">
                    <div class="sub-banner-content">
                        <h2>Trái cây<br>nội địa</h2>
                        <span>Xem ngay &rsaquo;</span>
                    </div>
                </a>
            </div>
            <div class="main-picture">
                <img class="active" style="width: 1000px; height: 400px;" src="{{ asset('images/banner1.png') }}" alt="Banner 1">
                <img style="width: 1000px; height: 400px;" src="{{ asset('images/banner2.png') }}" alt="Banner 2">
                <img style="width: 1000px; height: 400px;" src="{{ asset('images/banner3.png') }}" alt="Banner 3">
            </div>
        </div>
    </div>

    <div class="features-bar">
        <div class="feature-item">
            <div class="feature-icon">
                <i class="fa-solid fa-truck-fast"></i>
            </div>
            <div class="feature-text">
                <h4>Miễn phí giao hàng</h4>
                <p>Cho đơn từ 500k</p>
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div class="feature-text">
                <h4>Giao hàng nhanh 2h</h4>
                <p>Nội thành Hà Nội</p>
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">
                <i class="fa-solid fa-rotate-left"></i>
            </div>
            <div class="feature-text">
                <h4>Đổi trả dễ dàng</h4>
                <p>Trong 24h nếu lỗi</p>
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">
                <i class="fa-solid fa-credit-card"></i>
            </div>
            <div class="feature-text">
                <h4>Thanh toán tiện lợi</h4>
                <p>Nhiều hình thức</p>
            </div>
        </div>
    </div>

    <div class="product-section">
        <h2 class="product-title">Sản phẩm nổi bật</h2>
        
        <div class="product-grid">
            @if($products->count() > 0)
                @foreach ($products as $product)
                    <div class="product-item">
                        <div class="product-img-wrapper">
                            <img src="{{ asset('uploads/' . $product->anh) }}" alt="{{ $product->ten }}">
                        </div>
                        
                        <div class="product-name">
                            {{ $product->ten }}
                        </div>
                        
                        <div class="product-price">
                            {{ number_format($product->gia, 0, ',', '.') }}đ
                        </div>
                        
                        <div class="product-unit">
                            Đơn vị: {{ $product->don_vi }}
                        </div>
                        <a href="{{ route('client.product.detail', $product->id) }}" class="btn-view">
                            Xem chi tiết
                        </a>    
                    </div>  
                @endforeach
            @else
                <p style="text-align: left; padding-left: 15px;">Hiện tại chưa có sản phẩm nào.</p>
            @endif
        </div>

        @if ($products->lastPage() > 1)
        <div class="pagination">
            @if(!$products->onFirstPage())
                <a href="{{ $products->previousPageUrl() }}">&laquo; Trước</a>
            @endif

            @for($i = 1; $i <= $products->lastPage(); $i++)
                <a href="{{ $products->url($i) }}" class="{{ ($products->currentPage() == $i) ? 'active' : '' }}">
                    {{ $i }}
                </a>
            @endfor

            @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}">Sau &raquo;</a>
            @endif
        </div>
        @endif
    </div>

    @include('client.layouts.footer')

    <script>
        let currentIndex = 0;
        const images = document.querySelectorAll('.main-picture img');
        const totalImages = images.length;

        function showImage(index) {
            images.forEach(img => img.classList.remove('active'));
            currentIndex = (index + totalImages) % totalImages;
            images[currentIndex].classList.add('active');
        }

        function showNext() {
            showImage(currentIndex + 1);
        }

        function showPrev() {
            showImage(currentIndex - 1);
        }

        let autoSlide = setInterval(showNext, 3000);

        const bannerContainer = document.querySelector('.right-mid');
        if (bannerContainer) {
            bannerContainer.addEventListener('mouseenter', () => clearInterval(autoSlide));
            bannerContainer.addEventListener('mouseleave', () => {
                autoSlide = setInterval(showNext, 3000);
            });
        }
    </script>
</body>
</html>