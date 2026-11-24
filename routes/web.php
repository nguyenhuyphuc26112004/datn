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

Route::as('client.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/chi-tiet/{id}', [HomeController::class, 'detailProduct'])->name('product.detail');
    Route::get('/tim-kiem', [HomeController::class, 'search'])->name('search');

    Route::get('/trai-cay-viet-nam', [HomeController::class, 'vietnamFruits'])->name('category.vietnam');
    Route::get('/trai-cay-nhap-khau', [HomeController::class, 'importedFruits'])->name('category.imported');
    Route::get('/qua-say-kho', [HomeController::class, 'dryFruits'])->name('category.dry');
    Route::get('/gio-trai-cay', [HomeController::class, 'fruitBaskets'])->name('category.basket');
    Route::get('/do-uong-trai-cay', [HomeController::class, 'fruitDrinks'])->name('category.drink');

    Route::get('/dang-nhap', [ClientLoginController::class, 'showLoginForm'])->name('dangNhap');
    Route::post('/dang-nhap', [ClientLoginController::class, 'login'])->name('dangNhap.post');
    Route::post('/dang-xuat', [ClientLoginController::class, 'logout'])->name('dangXuat');

    Route::get('/quen-mat-khau', [ForgotPasswordController::class, 'showFormQuenMatKhau'])->name('quenMatKhau');
    Route::post('/quen-mat-khau', [ForgotPasswordController::class, 'guiMaOtp'])->name('quenMatKhau.post');
    Route::get('/xac-nhan-otp', [ForgotPasswordController::class, 'showFormXacNhanOtp'])->name('xacNhanOtp');
    Route::post('/dat-lai-mat-khau', [ForgotPasswordController::class, 'datLaiMatKhau'])->name('datLaiMatKhau.post');

    Route::get('/dang-ky', [RegisterController::class, 'showRegisterForm'])->name('dangKy');
    Route::post('/dang-ky', [RegisterController::class, 'register'])->name('dangKy.post');

    Route::get('/ho-so', [ProfileController::class, 'showProfile'])->name('hoSo');
    Route::post('/ho-so/cap-nhat', [ProfileController::class, 'updateProfile'])->name('hoSo.update');
    Route::get('/doi-mat-khau', [ProfileController::class, 'showDoiMatKhau'])->name('doiMatKhau');
    Route::post('/doi-mat-khau', [ProfileController::class, 'postDoiMatKhau'])->name('doiMatKhau.post');

    Route::get('/gio-hang', [CartController::class, 'index'])->name('gioHang');
    Route::post('/them-gio-hang', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/xoa-gio-hang', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/dat-hang', [OrderController::class, 'datHang'])->name('trangDatHang');
    Route::post('/dat-hang', [OrderController::class, 'xuLyDatHang'])->name('xuLyDatHang');

    Route::post('/kiem-tra-voucher', [VoucherController::class, 'apDung'])->name('voucher.apDung');
    Route::get('/danh-sach-voucher', [VoucherController::class, 'danhSach'])->name('voucher.danhSach');

    Route::get('/don-hang-cua-toi', [OrderController::class, 'donHangCuaToi'])->name('donHangCuaToi');
    Route::get('/chi-tiet-don-hang/{id}', [OrderController::class, 'chiTietDonHang'])->name('chiTietDonHang');
    Route::post('/don-hang/huy/{id}', [OrderController::class, 'huyDonHang'])->name('huyDonHang');
    Route::post('/don-hang/tra-hang/{id}', [OrderController::class, 'yeuCauTraHang'])->name('yeuCauTraHang');
});

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

    Route::as('admin.')->middleware(['admin_or_staff'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/thong-ke', [DashboardController::class, 'thongKe'])->name('thongKe');

        Route::prefix('san-pham')->as('sanpham.')->group(function () {
            Route::get('/', [SanPhamController::class, 'index'])->name('index');
            Route::get('/them', [SanPhamController::class, 'create'])->name('create');
            Route::post('/them', [SanPhamController::class, 'store'])->name('store');
            Route::get('/{id}/xem', [SanPhamController::class, 'show'])->name('show');
            Route::get('/{id}/sua', [SanPhamController::class, 'edit'])->name('edit');
            Route::put('/{id}/sua', [SanPhamController::class, 'update'])->name('update');
            Route::post('/{id}/nhap-kho', [SanPhamController::class, 'nhapKho'])->name('nhap_kho');
            Route::delete('/{id}/lo-hang/{loHangId}', [SanPhamController::class, 'destroyLoHang'])->name('lo_hang.destroy');
            Route::delete('/{id}/xoa', [SanPhamController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('nguoi-dung')->as('user.')->group(function () {
            Route::get('/', [NguoiDungController::class, 'index'])->name('index');
            Route::get('/them', [NguoiDungController::class, 'create'])->name('create');
            Route::post('/them', [NguoiDungController::class, 'store'])->name('store');
            Route::get('/{id}/xem', [NguoiDungController::class, 'show'])->name('show');
            Route::get('/{id}/sua', [NguoiDungController::class, 'edit'])->name('edit');
            Route::put('/{id}/sua', [NguoiDungController::class, 'update'])->name('update');
            Route::delete('/{id}/xoa', [NguoiDungController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('khuyen-mai')->as('khuyenmai.')->group(function () {
            Route::get('/', [KhuyenMaiController::class, 'index'])->name('index');
            Route::get('/them', [KhuyenMaiController::class, 'create'])->name('create');
            Route::post('/them', [KhuyenMaiController::class, 'store'])->name('store');
            Route::get('/{id}/sua', [KhuyenMaiController::class, 'edit'])->name('edit');
            Route::put('/{id}/sua', [KhuyenMaiController::class, 'update'])->name('update');
            Route::delete('/{id}/xoa', [KhuyenMaiController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('don-hang')->as('donhang.')->group(function () {
            Route::get('/', [DonHangController::class, 'index'])->name('index');
            Route::get('/{id}/chi-tiet', [DonHangController::class, 'show'])->name('show');
            Route::get('/{id}/sua', [DonHangController::class, 'edit'])->name('edit');
            Route::put('/{id}/sua', [DonHangController::class, 'update'])->name('update');
            Route::post('/{id}/xac-nhan-tien', [DonHangController::class, 'xacNhanTien'])->name('xacNhanTien');
            Route::get('/{id}/in-hoa-don', [DonHangController::class, 'inHoaDon'])->name('inHoaDon');
            Route::delete('/{id}/xoa', [DonHangController::class, 'destroy'])->name('destroy');
            Route::get('/{id}/xu-ly-tra-hang', [DonHangController::class, 'showXuLyTraHang'])->name('showXuLyTraHang');
            Route::post('/{id}/xu-ly-tra-hang', [DonHangController::class, 'xuLyTraHang'])->name('xuLyTraHang');
        });
    });
});
