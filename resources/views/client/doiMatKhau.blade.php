<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu</title>
    <!-- FontAwesome Icons (nếu cần icon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <style>
        body { background-color: #f4f4f4; margin: 0; padding: 0; font-family: sans-serif; }
        .box { display: flex; justify-content: center; align-items: center; min-height: 80vh; }
        #formDoiMatKhau { background: #fff; padding: 30px; border-radius: 8px; width: 400px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; margin-top: 0; }
        .dau_vao { margin-bottom: 18px; }
        .dau_vao p { margin: 0 0 5px 0; font-size: 14px; color: #555; font-weight: bold; }
        .dau_vao input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; outline: none; transition: border-color 0.3s; }
        .dau_vao input:focus { border-color: #28a745; }
        .dau_vao input:disabled { background-color: #f5f5f5; color: #999; cursor: not-allowed; border-color: #e2e2e2; }
        
        /* Style cho chữ báo lỗi nhỏ màu đỏ */
        .text-danger { color: #dc3545; font-size: 12px; margin-top: 5px; display: block; }
        
        .box button { width: 100%; padding: 12px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; transition: background 0.3s; }
        .box button:hover { background-color: #218838; }
        
        /* Thông báo lỗi chung (nếu sai mật khẩu cũ từ DB trả về) */
        .msg-error { color: #721c24; background: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 15px; font-weight: bold; font-size: 14px; }
    </style>
</head>
<body>
    @include('client.layouts.header')

<div class="box">
    <form id="formDoiMatKhau" action="{{ route('client.doiMatKhau.post') }}" method="post">
        @csrf
        <h2>Đổi mật khẩu</h2>

        @if(session('errorMsg'))
            <div class="msg-error">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('errorMsg') }}
            </div>
        @endif

        <div class="dau_vao">
            <p>Tên đăng nhập</p>
            <input type="text" value="{{ $user->ten_dang_nhap }}" disabled>
        </div>

        <div class="dau_vao">
            <p>Mật khẩu hiện tại</p>
            <input type="password" name="mat_khau" placeholder="Mật khẩu cũ">
            
            @if($errors->has('mat_khau'))
                <span class="text-danger">{{ $errors->first('mat_khau') }}</span>
            @endif
        </div>

        <div class="dau_vao">
            <p>Mật khẩu mới</p>
            <input type="password" name="matKhaumoi" placeholder="Mật khẩu mới">
            
            @if($errors->has('matKhaumoi'))
                <span class="text-danger">{{ $errors->first('matKhaumoi') }}</span>
            @endif
        </div>

        <div class="dau_vao">
            <p>Nhập lại mật khẩu mới</p>
            <input type="password" name="nhapLaiMK" placeholder="Xác nhận lại mật khẩu mới">
            
            @if($errors->has('nhapLaiMK'))
                <span class="text-danger">{{ $errors->first('nhapLaiMK') }}</span>
            @endif
        </div>

        <button type="submit" name="doimatkhau">Đổi mật khẩu</button>
    </form>
</div>

    @include('client.layouts.footer')

</body>
</html>