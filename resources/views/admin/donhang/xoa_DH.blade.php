<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa đơn hàng #{{ $donHang->id }}</title>
    <style>
        .delete-box { max-width: 500px; margin: 80px auto; padding: 25px; background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); font-family: Arial, sans-serif; }
        .title { border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px; }
        .warning { background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; font-size: 14px; }
        .actions { margin-top: 20px; display: flex; gap: 10px; }
        .btn-back { background-color: #1C8552; color: white; padding: 8px 18px; text-decoration: none; border-radius: 4px; font-size: 14px; }
        .btn-delete { background-color: #c5303a; color: white; border: none; padding: 8px 18px; border-radius: 4px; cursor: pointer; font-size: 14px; }
    </style>
</head>
<body>
    <div class="delete-box">
        <div class="title">
            <h2>Xóa đơn hàng #{{ $donHang->id }}</h2>
        </div>
        <div class="main">
            <div class="warning">
                <p style="margin:0;">Bạn có chắc chắn muốn xóa đơn hàng này? Thao tác này sẽ xóa toàn bộ dữ liệu chi tiết đơn hàng liên quan và không thể khôi phục.</p>
            </div>
            <div class="actions">
                <form action="{{ route('admin.donhang.destroy', $donHang->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <a href="{{ route('admin.donhang.index') }}" class="btn-back">Trở lại</a>
                    <button type="submit" class="btn-delete">Xóa vĩnh viễn</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>