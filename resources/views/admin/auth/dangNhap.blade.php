<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Quản Trị Hệ Thống</title>
    <style>
        body { 
            background-color: #fdfdf3; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            margin: 0; 
        }
        .box { 
            background: #ffffff; 
            padding: 35px 30px; 
            border-radius: 10px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.08); 
            width: 100%; 
            max-width: 400px; 
            border: 1px solid #eaeaea;
        }
        .box h2 { 
            text-align: center; 
            margin-bottom: 25px; 
            color: #2c3e50; 
            font-size: 24px;
            font-weight: 700;
        }
        .dau_vao { 
            margin-bottom: 18px; 
        }
        .dau_vao label { 
            display: block; 
            margin-bottom: 6px; 
            font-weight: 600; 
            color: #444; 
            font-size: 14px;
        }
        .dau_vao input { 
            width: 100%; 
            padding: 11px 12px; 
            border: 1px solid #cccccc; 
            border-radius: 6px; 
            box-sizing: border-box; 
            font-size: 14px;
            transition: border-color 0.2s;
        }
        .dau_vao input:focus {
            outline: none;
            border-color: #28a745;
        }
        .alert-danger { 
            color: #721c24; 
            background-color: #f8d7da; 
            border: 1px solid #f5c6cb; 
            padding: 10px; 
            border-radius: 6px; 
            margin-bottom: 20px; 
            font-size: 14px;
            text-align: center; 
        }
        .alert-success { 
            color: #155724; 
            background-color: #d4edda; 
            border: 1px solid #c3e6cb; 
            padding: 10px; 
            border-radius: 6px; 
            margin-bottom: 20px; 
            font-size: 14px;
            text-align: center; 
        }
        .error-text { 
            color: #dc3545; 
            font-size: 13px; 
            margin-top: 4px; 
        }
        button { 
            width: 100%; 
            padding: 12px; 
            background-color: #28a745; 
            color: white; 
            border: none; 
            border-radius: 6px; 
            font-weight: bold; 
            cursor: pointer; 
            font-size: 16px; 
            margin-top: 10px; 
            transition: background-color 0.2s;
        }
        button:hover { 
            background-color: #218838; 
        }
        .extra-links {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
        }
        .extra-links a {
            color: #6c757d;
            text-decoration: none;
        }
        .extra-links a:hover {
            color: #28a745;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="box">
    <form id="formDangNhap" action="{{ route('admin.login.submit') }}" method="POST">
        @csrf
        <h2>ĐĂNG NHẬP ADMIN</h2>

        @if(session('error'))
            <div class="alert-danger">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="dau_vao">
            <label for="ten_dang_nhap">Tên đăng nhập</label>
            <input type="text" id="ten_dang_nhap" name="ten_dang_nhap" value="{{ old('ten_dang_nhap') }}" placeholder="Nhập tên đăng nhập" required autofocus>
            @error('ten_dang_nhap')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="dau_vao">
            <label for="mat_khau">Mật khẩu</label>
            <input type="password" id="mat_khau" name="mat_khau" placeholder="Nhập mật khẩu" required>
            @error('mat_khau')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">Đăng Nhập</button>

        <div class="extra-links">
            <a href="{{ route('client.home') }}">← Quay lại trang chủ bán hàng</a>
        </div>
    </form>
</div>

</body>
</html>