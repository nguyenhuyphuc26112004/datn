<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ cá nhân</title>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <style>
        body {
            background-color: #fdfdf3;
            margin: 0;
        }

        .profile-wrapper {
            max-width: 500px;
            margin: 40px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: 1px solid #eee;
        }

        .profile-wrapper h2 {
            text-align: center;
            color: #28c361;
            margin-bottom: 25px;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 18px;
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            color: #555;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .form-group input[type="text"],
        .form-group input[type="email"] {
            width: 100%;
            padding: 11px 11px 11px 38px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 15px;
            outline: none;
            transition: border-color 0.3s;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="email"]:focus {
            border-color: #28c361;
        }

        .form-group input:disabled {
            background-color: #f5f5f5;
            color: #888;
            cursor: not-allowed;
        }

        .form-group i {
            position: absolute;
            left: 13px;
            top: 37px;
            color: #28c361;
            font-size: 16px;
        }

        /* Style cho chữ báo lỗi nhỏ màu đỏ dưới từng trường */
        .text-danger {
            color: #dc3545;
            font-size: 16px;
            margin-top: 5px;
            padding-left: 10px;
            display: block;
        }

        .gender-label {
            font-size: 14px;
            color: #555;
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
        }

        .gender-group {
            display: flex;
            gap: 25px;
            margin-bottom: 10px;
        }

        .gender-group label {
            cursor: pointer;
            font-size: 15px;
            color: #333;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Group chứa nút hành động */
        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 15px;
        }

        .btn-update {
            flex: 1;
            padding: 12px;
            background: #28c361;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.3s;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-update:hover {
            background: #219150;
        }

        .btn-password {
            flex: 1;
            padding: 12px;
            background: #f8f9fa;
            color: #495057;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 15px;
            text-decoration: none;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-sizing: border-box;
            transition: all 0.3s;
            font-weight: bold;
        }

        .btn-password:hover {
            background: #e2e6ea;
            color: #212529;
            border-color: #dae0e5;
        }

        /* Khối thông báo thành công ở trên cùng */
        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    @include('client.layouts.header')

    <div class="profile-wrapper">
        <h2><i class="fa-solid fa-user-gear"></i> Hồ sơ cá nhân</h2>

        {{-- Thông báo thành công hiển thị ở phía trên cùng --}}
        @if(session('successMsg'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i> {{ session('successMsg') }}
            </div>
        @endif

        <form action="{{ route('client.hoSo.update') }}" method="POST">
            @csrf

            <!-- Tên đăng nhập (Khóa không sửa) -->
            <div class="form-group">
                <label>Tên đăng nhập</label>
                <i class="fa-solid fa-user"></i>
                <input type="text" value="{{ $user->ten_dang_nhap }}" disabled title="Tên đăng nhập không thể thay đổi">
            </div>

            <!-- Họ và tên -->
            <div class="form-group">
                <label>Họ và tên</label>
                <i class="fa-solid fa-address-card"></i>
                <input type="text" name="hoVaTen" value="{{ old('hoVaTen', $user->ho_ten) }}" placeholder="Nhập họ và tên...">
            
                @error('hoVaTen')
                    <span class="text-danger"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Email</label>
                <i class="fa-solid fa-envelope"></i>
                <input type="text" name="email" value="{{ old('email', $user->email) }}" placeholder="Nhập email...">
                
                @error('email')
                    <span class="text-danger"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <!-- Giới tính -->
            <div class="form-group">
                <span class="gender-label">Giới tính:</span>
                <div class="gender-group">
                    <label>
                        <input type="radio" name="gioiTinh" value="Nam" {{ old('gioiTinh', $user->gioi_tinh) == 'Nam' ? 'checked' : '' }}> Nam
                    </label>
                    <label>
                        <input type="radio" name="gioiTinh" value="Nữ" {{ old('gioiTinh', $user->gioi_tinh) == 'Nữ' ? 'checked' : '' }}> Nữ
                    </label>
                </div>
               
            </div>

            <div class="btn-group">
                <button type="submit" name="btnUpdate" class="btn-update">
                    <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
                </button>
                
                <a href="{{ route('client.doiMatKhau') }}" class="btn-password">
                    <i class="fa-solid fa-key"></i> Đổi mật khẩu
                </a>
            </div>
        </form>
    </div>

    {{-- Footer --}}
    @include('client.layouts.footer')

</body>
</html>