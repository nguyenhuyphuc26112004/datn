# Website bán hàng - Laravel

Ứng dụng web bán hàng được xây dựng bằng Laravel. Dự án có chức năng xem sản phẩm, quản lý giỏ hàng, đặt hàng và quản lý đơn hàng, tồn kho theo lô.

## Yêu cầu

- PHP 8.2 trở lên và các extension Laravel yêu cầu
- Composer
- MySQL hoặc MariaDB
- Node.js và npm
- XAMPP, Laragon hoặc một môi trường tương đương có MySQL/MariaDB

Cơ sở dữ liệu mẫu được xuất từ MariaDB 10.11.

## Cài đặt và chạy trên Windows

Mở PowerShell tại thư mục dự án và thực hiện các bước sau.

### 1. Cài thư viện PHP và tạo file môi trường

```powershell
Copy-Item .env.example .env
composer install
php artisan key:generate
```

### 2. Tạo và nạp cơ sở dữ liệu

1. Khởi động MySQL.
2. Tạo database tên `do_an`, dùng charset `utf8mb4` và collation `utf8mb4_unicode_ci`.
3. Import file `database/demo_do_an.sql` vào database `do_an`.

### 3. Cấu hình kết nối database

Mở file `.env` và cập nhật các giá trị phù hợp với máy của bạn:

```dotenv
APP_NAME="Website ban hang"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=do_an
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

Nếu MySQL có mật khẩu, điền mật khẩu vào `DB_PASSWORD`. Sau khi chỉnh `.env`, chạy:

```powershell
php artisan config:clear
```

Mặc định `.env.example` dùng `MAIL_MAILER=log`, nên không cần tài khoản Gmail để chạy demo; nội dung email được ghi vào log của Laravel. Chỉ cấu hình SMTP trong `.env` cá nhân nếu cần gửi email thật. Các biến AWS có thể để trống khi dùng lưu trữ local.


### 4. Khởi động website

php artisan serve

Mở trình duyệt tại [http://127.0.0.1:8000](http://127.0.0.1:8000).

## Chạy giao diện ở chế độ phát triển

Để tự động cập nhật tài nguyên giao diện khi chỉnh sửa, chạy lệnh sau trong một cửa sổ PowerShell riêng:

```powershell
npm run dev
```

Giữ lệnh này hoạt động, sau đó chạy `php artisan serve` ở cửa sổ PowerShell khác.


