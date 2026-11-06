<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu | Fruit Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; }
        body { background: #fdfdf3; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .container { background: #fff; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); width: 100%; max-width: 400px; text-align: center; }
        .icon-circle { width: 80px; height: 80px; background: #e8f5e9; color: #28c361; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin: 0 auto 20px; font-size: 35px; }
        h2 { color: #333; margin-bottom: 10px; font-size: 24px; }
        p.desc { color: #666; font-size: 14px; margin-bottom: 25px; line-height: 1.5; }
        .error-msg { color: #dc3545; background: #f8d7da; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; border: 1px solid #f5c6cb; }
        .input-group { position: relative; margin-bottom: 20px; }
        .input-group i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #aaa; }
        input[type="email"] { width: 100%; padding: 12px 15px 12px 45px; border: 1px solid #ddd; border-radius: 8px; outline: none; transition: all 0.3s; font-size: 15px; }
        input[type="email"]:focus { border-color: #28c361; box-shadow: 0 0 8px rgba(40,195,97,0.1); }
        button { width: 100%; padding: 13px; background: #28c361; color: #fff; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #219150; }
        .back-link { display: inline-block; margin-top: 20px; text-decoration: none; color: #888; font-size: 14px; }
        .back-link:hover { color: #28c361; }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-circle"><i class="fa-solid fa-lock-open"></i></div>
        <form method="POST" action="{{ route('client.quenMatKhau.post') }}">
            @csrf
            <h2>Quên mật khẩu?</h2>
            <p class="desc">Nhập email đã đăng ký, hệ thống sẽ gửi mã OTP để bạn đặt lại mật khẩu.</p>
            
            @if(session('error'))
                <div class="error-msg">{{ session('error') }}</div>
            @endif
            @error('email')
                <div class="error-msg">{{ $message }}</div>
            @enderror

            <div class="input-group">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Nhập email của bạn" >
            </div>
            
            <button type="submit">Gửi mã xác nhận</button>
            
            <a href="{{ route('client.dangNhap') }}" class="back-link">
                <i class="fa-solid fa-arrow-left-long"></i> Quay lại đăng nhập
            </a>
        </form>
    </div>
</body>
</html>