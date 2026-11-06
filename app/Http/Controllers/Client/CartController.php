<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CtGioHang;
use App\Models\GioHang;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        if (!session()->has('idNguoiDung')) {
            return redirect()->route('client.dangNhap')->with('errorLogin', 'Vui lòng đăng nhập để thực hiện thao tác!');
        }

        $idNguoiDung = session('idNguoiDung');
        $idSanPham = $request->input('idSanPham');
        $soLuong = $request->input('soLuong');
        $action = $request->input('action', 'add');
        
        $sanPham = SanPham::where('id', $idSanPham)
            ->where('trang_thai', 1)
            ->whereDate('ngay_het_han', '>=', Carbon::today())
            ->first();

        if (!$sanPham) {
            return redirect()->route('client.home')->with('error', 'Sản phẩm không tồn tại, đã ngừng kinh doanh hoặc đã hết hạn!');
        }
    
        $giaChuan = (float)$sanPham->gia;

        if ($action === 'buy') {
            if ($soLuong > $sanPham->so_luong) {
                return back()->with('error', "Số lượng đặt vượt quá tồn kho! Sản phẩm '{$sanPham->ten}' chỉ còn lại {$sanPham->so_luong} sản phẩm.");
            }
            session()->put('buy_now', [
                $idSanPham => [
                    'ten'      => $sanPham->ten,
                    'gia'      => $giaChuan,
                    'so_luong' => $soLuong,
                    'anh'      => $sanPham->anh ?? ''
                ]
            ]);

            return redirect()->route('client.trangDatHang', ['type' => 'buy_now']);
        }
        
        $gioHang = GioHang::where('id_nguoi_dung', $idNguoiDung)->first();

        if ($gioHang) {
            $idGioHang = $gioHang->id;

            $cTGioHang = CtGioHang::where('id_gio_hang', $idGioHang)
                ->where('id_san_pham', $idSanPham)
                ->first();

            if ($cTGioHang) {
                $currentQuantity = $cTGioHang->so_luong + $soLuong;

                $cTGioHang->update([
                    'so_luong' => $currentQuantity
                ]);
            } else {
                CtGioHang::create([
                    'id_gio_hang' => $idGioHang,
                    'id_san_pham' => $idSanPham,
                    'so_luong'    => $soLuong,
                ]);
                $gioHang->increment('so_luong_sp');
            }
        } else {
            $gioHang = GioHang::create([
                'id_nguoi_dung' => $idNguoiDung,
                'so_luong_sp'   => 1,
            ]);

            if ($gioHang) {
                CtGioHang::create([
                    'id_gio_hang' => $gioHang->id,
                    'id_san_pham' => $idSanPham,
                    'so_luong'    => $soLuong,
                ]);
            } else {
                return redirect()->back()->with('errorCart', 'Lỗi: Không thể tạo mới giỏ hàng.');
            }
        }

        return redirect()->route('client.gioHang')->with('successCart', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    public function index()
    {
        if (!session()->has('idNguoiDung')) {
            return redirect()->route('client.dangNhap')->with('errorLogin', 'Vui lòng đăng nhập để xem giỏ hàng!');
        }
        $idNguoiDung = session('idNguoiDung');
        $gioHang = GioHang::where('id_nguoi_dung', $idNguoiDung)->first();
        $cTGioHang = [];
        if ($gioHang) {
            $cTGioHang = CtGioHang::join('san_pham', 'ct_gio_hang.id_san_pham', '=', 'san_pham.id')
                ->where('ct_gio_hang.id_gio_hang', $gioHang->id)
                ->select(
                    'ct_gio_hang.*',
                    'san_pham.ten as ten_san_pham',
                    'san_pham.anh as anh_san_pham',
                    'san_pham.gia as gia_san_pham',
                )
                ->get();
        }
        return view('client.cart', compact('gioHang', 'cTGioHang'));
    }

    public function remove(Request $request){
        if(!session()->has('idNguoiDung')){
            return redirect()->route('client.dangNhap')->with('errorLogin', 'Vui lòng đăng nhập!');
        }
        $idSanPham = $request->input('id_san_pham');
        $idGioHang = $request->input('id_gio_hang');
        $delete = CtGioHang::where('id_gio_hang', $idGioHang)
            ->where('id_san_pham', $idSanPham)
            ->delete();
        if($delete){
            GioHang::where('id', $idGioHang)->decrement('so_luong_sp');
        }
        return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }
}