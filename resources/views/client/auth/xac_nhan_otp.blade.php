<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận OTP | Fruit Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; }
        body { background: #fdfdf3; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .container { background: #fff; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); width: 100%; max-width: 420px; text-align: center; }
        .icon-circle { width: 70px; height: 70px; background: #e8f5e9; color: #28c361; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin: 0 auto 20px; font-size: 30px; }
        h2 { color: #333; margin-bottom: 10px; font-size: 24px; }
        p.desc { color: #666; font-size: 14px; margin-bottom: 25px; line-height: 1.5; }
        b.email-display { color: #28c361; word-break: break-all; }
        .error-msg { color: #dc3545; background: #f8d7da; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; border: 1px solid #f5c6cb; }
        .input-group { position: relative; margin-bottom: 15px; text-align: left; }
        .input-group i { position: absolute; left: 15px; top: 38px; color: #aaa; }
        .input-group label { display: block; font-size: 13px; color: #555; margin-bottom: 5px; margin-left: 5px; font-weight: 600; }
        input { width: 100%; padding: 12px 15px 12px 45px; border: 1px solid #ddd; border-radius: 8px; outline: none; transition: all 0.3s; font-size: 15px; }
        input:focus { border-color: #28c361; box-shadow: 0 0 8px rgba(40,195,97,0.1); }
        input[name="otp"] { letter-spacing: 4px; font-weight: bold; font-size: 18px; text-align: center; padding-left: 15px; }
        button { width: 100%; padding: 13px; background: #28c361; color: #fff; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: background 0.3s; margin-top: 10px; }
        button:hover { background: #219150; }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-circle"><i class="fa-solid fa-shield-halved"></i></div>
        <form method="POST" action="{{ route('client.datLaiMatKhau.post') }}">
            @csrf
            <h2>Xác thực OTP</h2>
            <p class="desc">Mã xác nhận đã gửi tới: <br><b class="email-display">{{ session('reset_email') }}</b></p>
            
            @if(session('error'))
                <div class="error-msg">{{ session('error') }}</div>
            @endif
            
            @if ($errors->any())
                <div class="error-msg">{{ $errors->first() }}</div>
            @endif

            <div class="input-group">
                <label>Mã xác thực (6 số)</label>
                <input type="text" name="otp" placeholder="••••••" required maxlength="6" autocomplete="off">
            </div>

            <div class="input-group">
                <label>Mật khẩu mới</label>
                <i class="fa-solid fa-key"></i>
                <input type="password" name="new_password" placeholder="Nhập mật khẩu mới" required>
            </div>

            <div class="input-group">
                <label>Xác nhận mật khẩu</label>
                <i class="fa-solid fa-check-double"></i>
                <input type="password" name="new_password_confirmation" placeholder="Nhập lại mật khẩu mới" required>
            </div>
            
            <button type="submit">Cập nhật mật khẩu</button>
        </form>
    </div>
</body>
</html>