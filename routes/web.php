<?php
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\Auth\LoginController as ClientLoginController;
use App\Http\Controllers\Client\Auth\RegisterController;
use App\Http\Controllers\Client\Auth\ForgotPasswordController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\VoucherController;
use App\Http\Controllers\Client\OrderController;

use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SanPhamController;
use App\Http\Controllers\Admin\NguoiDungController;
use App\Http\Controllers\Admin\DonHangController;
use App\Http\Controllers\Admin\KhuyenMaiController;
use Illuminate\Support\Facades\Route;

// ==========================================
// 1. ROUTE CLIENT (TRANG KHÁCH HÀNG)
// ==========================================
Route::as('client.')->group(function () {
    // Trang chủ & Chi tiết & Tìm kiếm
    Route::get('/', [HomeController::class, 'index'])->name('home'); // Hiển thị trang chủ khách hàng (Tên route: client.home)
    Route::get('/chi-tiet/{id}', [HomeController::class, 'detailProduct'])->name('product.detail'); // Xem chi tiết 1 sản phẩm theo ID (Tên route: client.product.detail)
    Route::get('/tim-kiem', [HomeController::class, 'search'])->name('search'); // Tìm kiếm sản phẩm theo từ khóa (Tên route: client.search)
    
    // Các danh mục sản phẩm
    Route::get('/trai-cay-viet-nam', [HomeController::class, 'vietnamFruits'])->name('category.vietnam'); // Xem danh sách Trái cây Việt Nam (Tên route: client.category.vietnam)
    Route::get('/trai-cay-nhap-khau', [HomeController::class, 'importedFruits'])->name('category.imported'); // Xem danh sách Trái cây nhập khẩu (Tên route: client.category.imported)
    Route::get('/qua-say-kho', [HomeController::class, 'dryFruits'])->name('category.dry'); // Xem danh sách Quả sấy khô (Tên route: client.category.dry)
    Route::get('/gio-trai-cay', [HomeController::class, 'fruitBaskets'])->name('category.basket'); // Xem danh sách Giỏ trái cây (Tên route: client.category.basket)
    Route::get('/do-uong-trai-cay', [HomeController::class, 'fruitDrinks'])->name('category.drink'); // Xem danh sách Đồ uống trái cây (Tên route: client.category.drink)

    // Đăng nhập & Đăng xuất Client
    Route::get('/dang-nhap', [ClientLoginController::class, 'showLoginForm'])->name('dangNhap'); // Hiển thị giao diện form đăng nhập khách hàng (Tên route: client.dangNhap)
    Route::post('/dang-nhap', [ClientLoginController::class, 'login'])->name('dangNhap.post'); // Xử lý xác thực đăng nhập khách hàng (Tên route: client.dangNhap.post)
    Route::post('/dang-xuat', [ClientLoginController::class, 'logout'])->name('dangXuat'); // Xử lý đăng xuất tài khoản khách hàng (Tên route: client.dangXuat)

    // Quên mật khẩu & OTP Client
    Route::get('/quen-mat-khau', [ForgotPasswordController::class, 'showFormQuenMatKhau'])->name('quenMatKhau'); // Hiển thị giao diện nhập email/SĐT quên mật khẩu (Tên route: client.quenMatKhau)
    Route::post('/quen-mat-khau', [ForgotPasswordController::class, 'guiMaOtp'])->name('quenMatKhau.post'); // Xử lý gửi mã OTP xác minh qua email/SĐT (Tên route: client.quenMatKhau.post)
    Route::get('/xac-nhan-otp', [ForgotPasswordController::class, 'showFormXacNhanOtp'])->name('xacNhanOtp'); // Hiển thị giao diện nhập mã OTP xác nhận (Tên route: client.xacNhanOtp)
    Route::post('/dat-lai-mat-khau', [ForgotPasswordController::class, 'datLaiMatKhau'])->name('datLaiMatKhau.post'); // Xử lý lưu mật khẩu mới sau khi xác thực OTP (Tên route: client.datLaiMatKhau.post)

    // Đăng ký Client
    Route::get('/dang-ky', [RegisterController::class, 'showRegisterForm'])->name('dangKy'); // Hiển thị giao diện form đăng ký tài khoản khách hàng (Tên route: client.dangKy)
    Route::post('/dang-ky', [RegisterController::class, 'register'])->name('dangKy.post'); // Xử lý lưu thông tin đăng ký tài khoản mới (Tên route: client.dangKy.post)

    // Quản lý Hồ sơ cá nhân
    Route::get('/ho-so', [ProfileController::class, 'showProfile'])->name('hoSo'); // Xem thông tin hồ sơ cá nhân của khách hàng (Tên route: client.hoSo)
    Route::post('/ho-so/cap-nhat', [ProfileController::class, 'updateProfile'])->name('hoSo.update'); // Cập nhật thông tin hồ sơ cá nhân (Tên route: client.hoSo.update)
    Route::get('/doi-mat-khau', [ProfileController::class, 'showDoiMatKhau'])->name('doiMatKhau'); // Hiển thị form đổi mật khẩu tài khoản (Tên route: client.doiMatKhau)
    Route::post('/doi-mat-khau', [ProfileController::class, 'postDoiMatKhau'])->name('doiMatKhau.post'); // Xử lý lưu thay đổi mật khẩu tài khoản (Tên route: client.doiMatKhau.post)

    // Giỏ hàng (Chỉ hỗ trợ xem và xóa sản phẩm)
    Route::get('/gio-hang', [CartController::class, 'index'])->name('gioHang'); // Xem danh sách các sản phẩm đang có trong giỏ hàng (Tên route: client.gioHang)
    Route::post('/them-gio-hang', [CartController::class, 'addToCart'])->name('cart.add'); // Thêm một sản phẩm mới vào giỏ hàng (Tên route: client.cart.add)
    Route::post('/xoa-gio-hang', [CartController::class, 'remove'])->name('cart.remove'); // Xóa một sản phẩm ra khỏi giỏ hàng (Tên route: client.cart.remove)

    // Đặt hàng & Thanh toán
    Route::get('/dat-hang', [OrderController::class, 'datHang'])->name('trangDatHang'); // Hiển thị giao diện trang thanh toán / đặt hàng (Tên route: client.trangDatHang)
    Route::post('/dat-hang', [OrderController::class, 'xuLyDatHang'])->name('xuLyDatHang'); // Xử lý lưu đơn hàng mới vào cơ sở dữ liệu (Tên route: client.xuLyDatHang)

    // Áp dụng mã khuyến mãi (AJAX)
    Route::post('/kiem-tra-voucher', [VoucherController::class, 'apDung'])->name('voucher.apDung'); // Kiểm tra và áp dụng mã giảm giá qua AJAX (Tên route: client.voucher.apDung)
    Route::get('/danh-sach-voucher', [VoucherController::class, 'danhSach'])->name('voucher.danhSach'); // Lấy danh sách mã khuyến mãi đang hiệu lực (Tên route: client.voucher.danhSach)

    // Quản lý Đơn hàng
    Route::get('/don-hang-cua-toi', [OrderController::class, 'donHangCuaToi'])->name('donHangCuaToi'); // Xem danh sách tất cả đơn hàng lịch sử của khách hàng (Tên route: client.donHangCuaToi)
    Route::get('/chi-tiet-don-hang/{id}', [OrderController::class, 'chiTietDonHang'])->name('chiTietDonHang'); // Xem chi tiết một đơn hàng cụ thể theo ID (Tên route: client.chiTietDonHang)
    Route::post('/don-hang/huy/{id}', [OrderController::class, 'huyDonHang'])->name('huyDonHang'); // Xử lý thay đổi trạng thái đơn hàng thành đã hủy (Tên route: client.huyDonHang)
    Route::post('/don-hang/tra-hang/{id}', [OrderController::class, 'yeuCauTraHang'])->name('yeuCauTraHang'); // Gửi yêu cầu trả hàng cho đơn đã nhận (Tên route: client.yeuCauTraHang)
});

// ==========================================
// 2. ROUTE ADMIN (TRANG QUẢN TRỊ)
// ==========================================
Route::prefix('admin')->group(function () {
    
    // Đăng nhập / Đăng xuất Admin
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login'); // Hiển thị form đăng nhập trang quản trị (URI: /admin/login | Tên route: admin.login)
    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login.submit'); // Xử lý đăng nhập tài khoản quản trị (URI: /admin/login | Tên route: admin.login.submit)
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout'); // Xử lý đăng xuất tài khoản quản trị (URI: /admin/logout | Tên route: admin.logout)

    // Các chức năng Admin
    Route::as('admin.')->middleware(['admin_or_staff'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // Xem trang tổng quan/bảng điều khiển Admin (URI: /admin/dashboard | Tên route: admin.dashboard)
        Route::get('/thong-ke', [DashboardController::class, 'thongKe'])->name('thongKe'); // Xem trang báo cáo thống kê doanh thu, đơn hàng (URI: /admin/thong-ke | Tên route: admin.thongKe)

        // Quản lý sản phẩm
        Route::prefix('san-pham')->as('sanpham.')->group(function () {
            Route::get('/', [SanPhamController::class, 'index'])->name('index'); // Xem danh sách tất cả sản phẩm (URI: /admin/san-pham | Tên route: admin.sanpham.index)
            Route::get('/them', [SanPhamController::class, 'create'])->name('create'); // Hiển thị form thêm sản phẩm mới (URI: /admin/san-pham/them | Tên route: admin.sanpham.create)
            Route::post('/them', [SanPhamController::class, 'store'])->name('store'); // Xử lý lưu thông tin sản phẩm mới vào CSDL (URI: /admin/san-pham/them | Tên route: admin.sanpham.store)
            Route::get('/{id}/xem', [SanPhamController::class, 'show'])->name('show'); // Xem thông tin chi tiết của 1 sản phẩm (URI: /admin/san-pham/{id}/xem | Tên route: admin.sanpham.show)
            Route::get('/{id}/sua', [SanPhamController::class, 'edit'])->name('edit'); // Hiển thị form chỉnh sửa thông tin sản phẩm (URI: /admin/san-pham/{id}/sua | Tên route: admin.sanpham.edit)
            Route::put('/{id}/sua', [SanPhamController::class, 'update'])->name('update'); // Xử lý cập nhật thông tin sản phẩm vào CSDL (URI: /admin/san-pham/{id}/sua | Tên route: admin.sanpham.update)
            Route::post('/{id}/nhap-kho', [SanPhamController::class, 'nhapKho'])->name('nhap_kho'); // Xử lý nhập thêm hàng vào kho (URI: /admin/san-pham/{id}/nhap-kho | Tên route: admin.sanpham.nhap_kho)
            Route::get('/{id}/lo-hang', [SanPhamController::class, 'danhSachLoHang'])->name('danhSachLoHang'); // Lấy danh sách các lô hàng của sản phẩm (URI: /admin/san-pham/{id}/lo-hang | Tên route: admin.sanpham.danhSachLoHang)
            Route::get('/{id}/xoa', [SanPhamController::class, 'delete'])->name('delete'); // Hiển thị giao diện/trang xác nhận xóa sản phẩm (URI: /admin/san-pham/{id}/xoa | Tên route: admin.sanpham.delete)
            Route::delete('/{id}/xoa', [SanPhamController::class, 'destroy'])->name('destroy'); // Xử lý xóa vĩnh viễn sản phẩm khỏi CSDL (URI: /admin/san-pham/{id}/xoa | Tên route: admin.sanpham.destroy)
        });

        // Quản lý người dùng
        Route::prefix('nguoi-dung')->as('user.')->group(function () {
            Route::get('/', [NguoiDungController::class, 'index'])->name('index'); // Xem danh sách tài khoản người dùng/khách hàng (URI: /admin/nguoi-dung | Tên route: admin.user.index)
            Route::get('/them', [NguoiDungController::class, 'create'])->name('create'); // Hiển thị form tạo tài khoản người dùng mới (URI: /admin/nguoi-dung/them | Tên route: admin.user.create)
            Route::post('/them', [NguoiDungController::class, 'store'])->name('store'); // Xử lý lưu thông tin tài khoản mới (URI: /admin/nguoi-dung/them | Tên route: admin.user.store)
            Route::get('/{id}/xem', [NguoiDungController::class, 'show'])->name('show'); // Xem thông tin chi tiết của 1 người dùng (URI: /admin/nguoi-dung/{id}/xem | Tên route: admin.user.show)
            Route::get('/{id}/sua', [NguoiDungController::class, 'edit'])->name('edit'); // Hiển thị form chỉnh sửa thông tin người dùng (URI: /admin/nguoi-dung/{id}/sua | Tên route: admin.user.edit)
            Route::put('/{id}/sua', [NguoiDungController::class, 'update'])->name('update'); // Xử lý cập nhật thông tin người dùng (URI: /admin/nguoi-dung/{id}/sua | Tên route: admin.user.update)
            Route::patch('/{id}/khoa', [NguoiDungController::class, 'toggleStatus'])->name('toggleStatus'); // Khóa hoặc mở khóa tài khoản người dùng (URI: /admin/nguoi-dung/{id}/khoa | Tên route: admin.user.toggleStatus)
            Route::delete('/{id}/xoa', [NguoiDungController::class, 'destroy'])->name('destroy'); // Xử lý xóa tài khoản người dùng khỏi CSDL (URI: /admin/nguoi-dung/{id}/xoa | Tên route: admin.user.destroy)
        });

        // Quản lý khuyến mãi (Chỉ Admin)
        Route::prefix('khuyen-mai')->as('khuyenmai.')->group(function () {
            Route::get('/', [KhuyenMaiController::class, 'index'])->name('index'); // Xem danh sách các mã khuyến mãi/voucher (URI: /admin/khuyen-mai | Tên route: admin.khuyenmai.index)
            Route::get('/them', [KhuyenMaiController::class, 'create'])->name('create'); // Hiển thị form tạo mã khuyến mãi mới (URI: /admin/khuyen-mai/them | Tên route: admin.khuyenmai.create)
            Route::post('/them', [KhuyenMaiController::class, 'store'])->name('store'); // Xử lý lưu mã khuyến mãi mới vào CSDL (URI: /admin/khuyen-mai/them | Tên route: admin.khuyenmai.store)
            Route::get('/{id}/sua', [KhuyenMaiController::class, 'edit'])->name('edit'); // Hiển thị form chỉnh sửa mã khuyến mãi (URI: /admin/khuyen-mai/{id}/sua | Tên route: admin.khuyenmai.edit)
            Route::put('/{id}/sua', [KhuyenMaiController::class, 'update'])->name('update'); // Xử lý cập nhật thông tin mã khuyến mãi (URI: /admin/khuyen-mai/{id}/sua | Tên route: admin.khuyenmai.update)
            Route::delete('/{id}/xoa', [KhuyenMaiController::class, 'destroy'])->name('destroy'); // Xử lý xóa mã khuyến mãi (URI: /admin/khuyen-mai/{id}/xoa | Tên route: admin.khuyenmai.destroy)
        });

        // Quản lý đơn hàng
        Route::prefix('don-hang')->as('donhang.')->group(function () {
            Route::get('/', [DonHangController::class, 'index'])->name('index'); // Xem danh sách tất cả các đơn hàng mua từ khách hàng (URI: /admin/don-hang | Tên route: admin.donhang.index)
            Route::get('/{id}/chi-tiet', [DonHangController::class, 'show'])->name('show'); // Xem chi tiết đơn hàng + danh sách sản phẩm (URI: /admin/don-hang/{id}/chi-tiet | Tên route: admin.donhang.show)
            Route::get('/{id}/sua', [DonHangController::class, 'edit'])->name('edit'); // Hiển thị form cập nhật trạng thái đơn hàng (Đã giao, Đang xử lý,...) (URI: /admin/don-hang/{id}/sua | Tên route: admin.donhang.edit)
            Route::put('/{id}/sua', [DonHangController::class, 'update'])->name('update'); // Xử lý lưu trạng thái đơn hàng mới cập nhật (URI: /admin/don-hang/{id}/sua | Tên route: admin.donhang.update)
            Route::post('/{id}/xac-nhan-tien', [DonHangController::class, 'xacNhanTien'])->name('xacNhanTien'); // Xác nhận đã thanh toán tiền (VietQR)
            Route::get('/{id}/in-hoa-don', [DonHangController::class, 'inHoaDon'])->name('inHoaDon'); // In phiếu đóng gói và hóa đơn giao hàng
            Route::get('/{id}/xoa', [DonHangController::class, 'confirmDelete'])->name('confirmDelete'); // Hiển thị trang/modal xác nhận xóa đơn hàng (URI: /admin/don-hang/{id}/xoa | Tên route: admin.donhang.confirmDelete)
            Route::delete('/{id}/xoa', [DonHangController::class, 'destroy'])->name('destroy'); // Xử lý xóa đơn hàng khỏi hệ thống (URI: /admin/don-hang/{id}/xoa | Tên route: admin.donhang.destroy)
            Route::get('/{id}/xu-ly-tra-hang', [DonHangController::class, 'showXuLyTraHang'])->name('showXuLyTraHang'); // Hiển thị form xử lý yêu cầu trả hàng (URI: /admin/don-hang/{id}/xu-ly-tra-hang | Tên route: admin.donhang.showXuLyTraHang)
            Route::post('/{id}/xu-ly-tra-hang', [DonHangController::class, 'xuLyTraHang'])->name('xuLyTraHang'); // Xử lý duyệt hoặc từ chối trả hàng (URI: /admin/don-hang/{id}/xu-ly-tra-hang | Tên route: admin.donhang.xuLyTraHang)
        });

    });
});