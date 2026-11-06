<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <title>Document</title>
</head>
<body>
    <div class="header">
        <div class="info-header">
            <div class="left-info-header">
                Chào mừng bạn ghé thăm shop
            </div>
            <div class="right-info-header">
                <div class="right-info">
                    <a href="hoSo.php" style="color: inherit; text-decoration: none;">
                        <i class="fa-solid fa-circle-user"></i>
                    </a>
                    <p>
                        {{ session()->has('tenDangNhap') ? 'Chào, ' . session('tenDangNhap') : 'Tài khoản' }}
                    </p>
                    <div class="log">
                        @if (!session()->has('tenDangNhap'))
                            <div class="login"><a href="{{ route('client.dangNhap') }}">Đăng nhập</a></div>
                            <div class="login"><a href="{{ route('client.dangKy') }}">Đăng ký</a></div>
                        @else
                            <div class="login"><a href="{{ route('client.hoSo') }}">Hồ sơ cá nhân</a></div>
                            <div class="login"><a href="{{ route('client.doiMatKhau') }}">Đổi mật khẩu</a></div>
                            <div class="login"><a href="{{ route('client.gioHang') }}">Giỏ hàng</a></div>
                            <div class="login"><a href="{{ route('client.donHangCuaToi') }}">Đơn hàng</a></div>                            
                            <div class="login">
                                <form id="client-logout-form" action="{{ route('client.dangXuat') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                <a href="javascript:void(0);" onclick="document.getElementById('client-logout-form').submit();">Đăng xuất</a>
                            </div>
                        @endif                   
                    </div>
                </div>
                
                <div class="account">
                    <i class="fa-solid fa-location-dot"></i>
                    <p>Hệ thống của hàng</p>
                </div>
            </div>
        </div>
        <div class="mid-header">
            <div>
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo">            
            </div>

            <form action="{{ route('client.search') }}" method="GET" class="search-box">
                <input type="text" name="query" value="{{ request('query') }}" placeholder="Bạn muốn tìm hoa quả gì hôm nay?">
                <button type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
            <div class="header-contact">
                <i class="fa-solid fa-headset"></i>
                <div class="contact-txt">
                    <p>Hỗ trợ tận tâm</p>
                    <span>0368.334.112</span>
                </div>
            </div>
        </div>
        <div class="nav">
            <div class="left-nav">
                <div class="list">
                    <i class="fa-solid fa-list"></i> <p>DANH MỤC SẢN PHẨM</p>
                </div>
                <a href="{{ route('client.home') }}">Trang chủ</a>
                <a href="">Giới thiệu</a>
                <a href="">Khuyến mãi</a>
                <a href="">Tin tức</a>
                <a href="">Tuyển dụng</a>
                <a href="">Liên hệ</a>
            </div> 
            <div class="right-nav">
                <a href="{{ route('client.gioHang') }}">Giỏ hàng</a>
                <div class="gioHang">
                    <a href="{{ route('client.gioHang') }}" style="color: inherit; text-decoration: none;">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </a>
                </div>
            </div>  
        </div>        
    </div>
</body>
</html>