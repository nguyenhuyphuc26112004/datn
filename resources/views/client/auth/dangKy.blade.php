<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản</title>
    <link rel="stylesheet" href="{{ asset('css/dangKyClient.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        .register { height: auto; min-height: 550px; }
        .gender-box { display: flex; align-items: center; gap: 20px; margin: 5px 0 5px 10px; color: #555; font-size: 14px; }
        .gender-option { display: flex; align-items: center; cursor: pointer; }
        .gender-option input { width: auto !important; margin-right: 5px; cursor: pointer; }
        .input-box.has-error input { border: 1px solid red; }
        .input-box.has-error .error { display: block; color: red; font-size: 12px; margin-top: 5px; }
        .error span { color: red; font-size: 12px; }
    </style>
</head>
<body>
<div class="page">
    <div class="register">
        <div class="left-register">
            <h2>Đăng ký</h2>

            

            <form action="{{ route('client.dangKy.post') }}" method="POST" id="registerForm" novalidate>
                @csrf
                
                <div class="input-box {{ $errors->has('hoVaTen') ? 'has-error' : '' }}">
                    <input type="text" name="hoVaTen" placeholder="Họ và tên" value="{{ old('hoVaTen') }}">
                    <i class="fa-solid fa-address-card"></i>
                    <div class="error"><span>{{ $errors->first('hoVaTen') }}</span></div>
                </div>

                <div class="input-box {{ $errors->has('email') ? 'has-error' : '' }}">
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}">
                    <i class="fa-solid fa-envelope"></i>
                    <div class="error"><span>{{ $errors->first('email') }}</span></div>
                </div>

                <div class="input-box {{ $errors->has('tenDangNhap') ? 'has-error' : '' }}">
                    <input type="text" name="tenDangNhap" placeholder="Tên đăng nhập" value="{{ old('tenDangNhap') }}">
                    <i class="fa-solid fa-user"></i>
                    <div class="error"><span>{{ $errors->first('tenDangNhap') }}</span></div>
                </div>

                <div class="input-box {{ $errors->has('matKhau') ? 'has-error' : '' }}">
                    <input type="password" name="matKhau" id = "matKhau" placeholder="Mật khẩu">
                    <i class="fa-solid fa-lock"></i>
                    <div class="error"><span>{{ $errors->first('matKhau') }}</span></div>
                </div>

                <div class="input-box {{ $errors->has('nhapLaiMatKhau') ? 'has-error' : '' }}">
                    <input type="password" name="nhapLaiMatKhau" id="nhapLaiMk" placeholder="Nhập lại mật khẩu">
                    <i class="fa-solid fa-shield-halved"></i>
                    <div class="error"><span>{{ $errors->first('nhapLaiMatKhau') }}</span></div>
                </div>
                <div>
                    <label>
                        <input type="checkbox" name="" id="show_matKhau"> Hiện mật khẩu
                    </label>
                </div>

                <div class="gender-box">
                    <span>Giới tính:</span>
                    <label class="gender-option">
                        <input type="radio" name="gioiTinh" value="nam" {{ old('gioiTinh', 'nam') == 'nam' ? 'checked' : '' }}> Nam
                    </label>
                    <label class="gender-option">
                        <input type="radio" name="gioiTinh" value="nu" {{ old('gioiTinh') == 'nu' ? 'checked' : '' }}> Nữ
                    </label>
                </div>
                <div class="error" style="margin: 0 0 15px 10px;">
                    <span>{{ $errors->first('gioiTinh') }}</span>
                </div>

                <button type="submit">Đăng ký</button>
            </form>
        </div>
        <div class="right-register">
            <h2>Welcome!</h2>
            <p>Đã có tài khoản?</p>
            <button onclick="location.href='{{ route('client.dangNhap') }}'">Đăng nhập ngay</button>
        </div>
    </div>
</div>

<script>

const form = document.getElementById("registerForm");

form.addEventListener("submit", function (e) {
    let isValid = true;

    const fieldNames = ["hoVaTen", "email", "tenDangNhap", "matKhau", "nhapLaiMatKhau"];
    fieldNames.forEach(name => {
        const input = form.querySelector(`[name="${name}"]`);
        const box = input.closest(".input-box");
        const span = box ? box.querySelector(".error span") : null;
        const value = input.value.trim();
        let errorMessage = "";

        if (value === "") {
            errorMessage = "Vui lòng không để trống trường này!";
        } else if (name === "email" && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            errorMessage = "Email không hợp lệ.";
        } else if (name === "matKhau" && input.value.length < 8) {
            errorMessage = "Mật khẩu tối thiểu 8 ký tự.";
        } else if (name === "matKhau" && input.value.length > 32) {
            errorMessage = "Mật khẩu tối đa 32 ký tự.";
        } else if (
            name === "nhapLaiMatKhau" &&
            input.value !== form.querySelector('[name="matKhau"]').value
        ) {
            errorMessage = "Mật khẩu nhập lại không khớp.";
        }

        if (box) box.classList.toggle("has-error", Boolean(errorMessage));
        if (span) span.textContent = errorMessage;
        if (errorMessage) {
            isValid = false;
        }
    });

    const genderChecked = form.querySelector("input[name='gioiTinh']:checked");
    const genderErrorSpan = document.querySelector(".gender-box + .error span");
    if (!genderChecked) {
        if (genderErrorSpan) genderErrorSpan.textContent = "Vui lòng chọn giới tính!";
        isValid = false;
    } else if (genderErrorSpan) {
        genderErrorSpan.textContent = "";
    }

    if (!isValid) {
        e.preventDefault();
    }
});

form.querySelectorAll("input").forEach(input => {
    input.addEventListener("input", function() {
        const box = this.closest(".input-box");
        if (box) {
            box.classList.remove("has-error");
            const span = box.querySelector(".error span");
            if (span) span.textContent = "";
        }
    });

    if (input.type === 'radio') {
        input.addEventListener("change", function() {
            const genderErrorSpan = document.querySelector(".gender-box + .error span");
            if (genderErrorSpan) genderErrorSpan.textContent = "";
        });
    }
});
</script>
</body>
</html>