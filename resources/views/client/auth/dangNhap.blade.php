<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - TMDT</title>
    <link rel="stylesheet" href="{{ asset('css/dangNhapClient.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
<div class="page">
    <div class="login">
        <div class="left-login">
            <h2>Hello, Welcome</h2>
            <p>Bạn chưa có tài khoản!</p>
            <button onclick="location.href='{{ route('client.dangKy') }}'">Đăng ký ngay</button>
        </div>

        <div class="right-login">
            <h2>Đăng nhập</h2>

            @if (session('errorLogin'))
                <div class="error-summary" style="color: #dc3545; background: #f8d7da; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ session('errorLogin') }}
                </div>
            @endif

            @if (session('success') || session('successLogin') || session('successRegister'))
                <div class="error-summary" style="color: #155724; background: #d4edda; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    <i class="fa-solid fa-check-circle"></i> 
                    {{ session('success') ?? session('successLogin') ?? session('successRegister') }}
                </div>
            @endif
            
            <form action="{{ route('client.dangNhap.post') }}" method="POST">
                @csrf

                <div class="input-box {{ $errors->has('tenDangNhap') ? 'has-error' : '' }}">
                    <input type="text" name="tenDangNhap" value="{{ old('tenDangNhap') }}" placeholder="Tên đăng nhập">
                    <i class="fa-solid fa-user"></i>
                    <div class="error"><span>{{ $errors->first('tenDangNhap') }}</span></div>
                </div>

                <div class="input-box {{ $errors->has('matKhau') ? 'has-error' : '' }}">
                    <input type="password" name="matKhau" placeholder="Mật khẩu">
                    <i class="fa-solid fa-lock"></i>
                    <div class="error"><span>{{ $errors->first('matKhau') }}</span></div>
                </div>

                <a href="{{ route('client.quenMatKhau') }}" style="font-size: 13px; text-decoration: none; margin-bottom: 10px; display: block; color: #007bff;">Quên mật khẩu?</a>
                <button type="submit">Đăng nhập</button>
            </form>

            <div class="divider">
                <span>Hoặc đăng nhập với</span>
            </div>

            <div class="list-icon">
                <div class="icon">
                    <i class="fa-brands fa-facebook"></i> 
                    <i class="fa-brands fa-google"></i>
                    <i class="fa-brands fa-instagram"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const inputs = form.querySelectorAll("input");

    form.addEventListener("submit", function (e) {
        let isValid = true;

        inputs.forEach(input => {
            const box = input.closest(".input-box");
            const span = box.querySelector(".error span");

            if (input.value.trim() === "") {
                box.classList.add("has-error");
                if (input.name === "tenDangNhap") {
                    span.textContent = "Tên đăng nhập không được để trống";
                } else if (input.name === "matKhau") {
                    span.textContent = "Mật khẩu không được để trống";
                }
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
        }
    });

    inputs.forEach(input => {
        input.addEventListener("input", function () {
            const box = this.closest(".input-box");
            if (box.classList.contains("has-error")) {
                box.classList.remove("has-error");
            }
        });
    });
});
</script>
</body>
</html>