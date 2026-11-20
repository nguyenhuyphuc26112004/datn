<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Xóa sản phầm id = {{ $product->id }}</h2>
    <form action="{{ route('admin.sanpham.destroy', ['id' => $product->id] + request()->query()) }}" method="post">
        @csrf
        @method('DELETE')
        <a href="{{ route('admin.sanpham.index', request()->query()) }}">Trở lại</a>
        <button type="submit">Xóa</button>
    </form>
</body>
</html>