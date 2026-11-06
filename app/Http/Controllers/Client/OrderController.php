<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GioHang;
use App\Models\DonHang;
use App\Models\CtDonHang;
use App\Models\SanPham;
use App\Models\KhuyenMai;
use App\Models\NguoiDung;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderSuccessMail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\LoHang;      
class OrderController extends Controller
{
    private $shopLat = 21.04753;
    private $shopLng = 105.77797;

    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; 

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) 
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    private function tinhPhiVanChuyen($tongTien, $lat = null, $lng = null, $diaChi = '')
    {
        if ($tongTien >= 300000 || $tongTien <= 0) return 0;

        if (!empty($lat) && !empty($lng)) {
            $km = $this->calculateDistance(
                $this->shopLat, 
                $this->shopLng, 
                (float)$lat, 
                (float)$lng
            );
            return match (true) {
                $km <= 5  => 0,
                $km <= 10 => 12000,
                $km <= 15 => 20000,
                $km <= 30 => 30000,
                $km <= 50 => 40000,
                default   => 50000,
            };
        }

        $c = Str::ascii(mb_strtolower($diaChi));
        $rates = [
            0     => ['co nhue', 'duc thang', 'tang thiet giap', 'thuy phuong', 'xuan dinh', 'bac tu liem', 'mo dia chat'],
            12000 => ['cau giay', 'nam tu liem', 'tay ho'],
            20000 => ['ba dinh', 'dong da', 'thanh xuan', 'hoan kiem', 'hai ba trung'],
            30000 => ['hoang mai', 'ha dong', 'long bien', 'hoai duc', 'dan phuong', 'dong anh', 'thanh tri', 'me linh', 'gia lam'],
            40000 => ['soc son', 'thach that', 'quoc oai', 'chuong my', 'son tay', 'ba vi', 'thuong tin'],
        ];

        foreach ($rates as $fee => $places) {
            if (Str::contains($c, $places)) 
                return $fee;
        }

        return Str::contains($c, ['ha noi', 'hanoi']) ? 25000 : 50000;
    }
    public function datHang(Request $request)
    {
        // 1. Kiểm tra đăng nhập
        if (!session()->has('idNguoiDung')) {
            return redirect()->route('client.dangNhap')->with('errorLogin', 'Vui lòng đăng nhập để thực hiện đặt hàng!');
        }

        $idNguoiDung = session('idNguoiDung');
        $user = NguoiDung::findOrFail($idNguoiDung);
        
        $type = $request->input('type', 'cart');
        $cTGioHang = [];

        if($type === 'buy_now' && session()->has('buy_now')){
            $buyNow = session('buy_now');

            foreach ($buyNow as $idSp => $item) {
                $cTGioHang[] = [
                    'id_san_pham' => $idSp,
                    'so_luong'    => $item['so_luong'] ?? 1,
                    'gia'         => (float)($item['gia'] ?? 0),
                    'ten'         => $item['ten'] ?? 'Sản phẩm',
                    'anh'         => $item['anh'] ?? 'default.jpg',
                ];
            }
        } 
        else {
            $gioHang = GioHang::where('id_nguoi_dung', $idNguoiDung)
                ->with('ctGioHangs.sanPham')
                ->first();

            if (!$gioHang || $gioHang->ctGioHangs->isEmpty()) {
                return redirect()->route('client.gioHang')->with('errorCart', 'Giỏ hàng của bạn đang trống!');
            }

            $cartErrors = [];
            foreach ($gioHang->ctGioHangs as $ct) {
                $sanPham = $ct->sanPham;
                
                if (!$sanPham) {
                    $cartErrors[] = "Có sản phẩm trong giỏ hàng không còn tồn tại!";
                    continue;
                }
                if ($sanPham->so_luong == 0) {
                    $cartErrors[] = "Sản phẩm '{$sanPham->ten}' hiện đã hết hàng!";
                    continue;
                }
                if (!empty($sanPham->ngay_het_han) && Carbon::parse($sanPham->ngay_het_han)->lt(now())) {
                    $cartErrors[] = "Sản phẩm '{$sanPham->ten}' đã hết hạn sử dụng!";
                    continue;
                }
                if ($sanPham->trang_thai == 0) {
                    $cartErrors[] = "Sản phẩm '{$sanPham->ten}' hiện đã ngừng kinh doanh!";
                    continue;
                }
                if ($sanPham->so_luong < $ct->so_luong) {
                    $cartErrors[] = "Sản phẩm '{$sanPham->ten}' không đủ số lượng trong kho (chỉ còn {$sanPham->so_luong} sản phẩm, bạn đang đặt {$ct->so_luong})!";
                    continue;
                }

                $giaBan = $sanPham->gia ?? 0;

                $cTGioHang[] = [
                    'id_san_pham' => $ct->id_san_pham,
                    'so_luong'    => $ct->so_luong,
                    'gia'         => (float)$giaBan,
                    'ten'         => $sanPham->ten,
                    'anh'         => $sanPham->anh,
                ];
            }

            if (!empty($cartErrors)) {
                return redirect()->route('client.gioHang')->with('errorCart', $cartErrors);
            }

            if (empty($cTGioHang)) {
                return redirect()->route('client.gioHang')->with('errorCart', 'Giỏ hàng của bạn đang trống');
            }
        }

        $tongTienTamTinh = 0;
        foreach ($cTGioHang as $it) {
            $tongTienTamTinh += $it['gia'] * $it['so_luong'];
        }

        $latUser = $request->input('latitude', $request->input('lat'));
        $lngUser = $request->input('longitude', $request->input('lng'));
        $diaChiUser = $request->input('address');
        
        $phiVanChuyen = $this->tinhPhiVanChuyen($tongTienTamTinh, $latUser, $lngUser, $diaChiUser);

        $maChuyenKhoan = 'DH' . time();

        return view('client.trangDatHang', compact(
            'user',
            'cTGioHang',
            'type',
            'maChuyenKhoan',
            'phiVanChuyen'
        ));
    }

    public function xuLyDatHang(Request $request)
    {
        if (!session()->has('idNguoiDung')) {
            return redirect()->route('client.dangNhap')->with('errorLogin', 'Vui lòng đăng nhập!');
        }

        $idNguoiDung = session('idNguoiDung');

        $request->validate([
            'name'           => 'required|string|max:255',
            'sdt'            => 'required|string|max:20',
            'email'          => 'required|email|max:255',
            'address'        => 'required|string|max:255',
            'payment_method' => 'required|in:COD,ONLINE',
            'latitude'       => 'nullable|numeric|between:-90,90',
            'longitude'      => 'nullable|numeric|between:-180,180',
        ]);

        $checkoutType = $request->input('checkout_type', 'cart');
        $rawOrderItems = [];

        if ($checkoutType === 'buy_now' && session()->has('buy_now')) {
            $buyNow = session('buy_now');
            foreach ($buyNow as $idSp => $item) {
                $rawOrderItems[] = [
                    'id_san_pham' => $idSp,
                    'so_luong'    => $item['so_luong'],
                ];
            }
        } else {
            $gioHang = GioHang::where('id_nguoi_dung', $idNguoiDung)
                ->with('ctGioHangs')
                ->first();

            if (!$gioHang || $gioHang->ctGioHangs->isEmpty()) {
                return redirect()->route('client.gioHang')->with('errorCart', 'Giỏ hàng của bạn đang trống!');
            }

            foreach ($gioHang->ctGioHangs as $ct) {
                $rawOrderItems[] = [
                    'id_san_pham' => $ct->id_san_pham,
                    'so_luong'    => $ct->so_luong,
                ];
            }
        }

        DB::beginTransaction();
        try {
            $orderItems  = [];
            $tongTienGoc = 0;
            $cartErrors  = [];

            foreach ($rawOrderItems as $raw) {
                $sp = SanPham::lockForUpdate()->find($raw['id_san_pham']);

                if (!$sp) {
                    $cartErrors[] = "Sản phẩm không còn tồn tại!";
                    continue;
                }
                if ($sp->trang_thai == 0) {
                    $cartErrors[] = "Sản phẩm '{$sp->ten}' hiện đã ngừng kinh doanh!";
                    continue;
                }
                if (!empty($sp->ngay_het_han) && Carbon::parse($sp->ngay_het_han)->lt(Carbon::today())) {
                    $cartErrors[] = "Sản phẩm '{$sp->ten}' đã hết hạn sử dụng!";
                    continue;
                }

                $tongKhaDung = $sp->loHangsKhaDung()->sum('so_luong_con_lai');
                    if ($tongKhaDung < $raw['so_luong']) {
                        $cartErrors[] = "Sản phẩm '{$sp->ten}' không đủ tồn kho (chỉ còn {$tongKhaDung} sản phẩm trong hạn, bạn đang đặt {$raw['so_luong']})!";
                        continue;
                    }
                $giaHienTai = (float)($sp->gia ?? 0);                
                $orderItems[] = [
                    'san_pham' => $sp,
                    'so_luong' => $raw['so_luong'],
                    'gia'      => $giaHienTai,
                ];

                $tongTienGoc += $giaHienTai * $raw['so_luong'];
            }

            if (!empty($cartErrors)) {
                DB::rollBack();
                if ($checkoutType === 'buy_now') {
                    $idSp = array_key_first(session('buy_now', []));
                    return redirect()->route('client.product.detail', $idSp)->with('error', implode(' ', $cartErrors)); 
                }
                return redirect()->route('client.gioHang')->with('errorCart', $cartErrors);
            }

            $tienGiam = 0;
            $maKhuyenMai = trim($request->input('ma_khuyen_mai'));
            if (!empty($maKhuyenMai)) {
                $km = KhuyenMai::where('ma_khuyen_mai', $maKhuyenMai)
                    ->lockForUpdate()
                    ->first();

                if ($km) {
                    $tienGiam = $km->tinhTienGiam($tongTienGoc);
                    $km->increment('da_dung');
                }
            }
            
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            $diaChi = $request->input('address');
            $phiVanChuyen = $this->tinhPhiVanChuyen($tongTienGoc, $latitude, $longitude, $diaChi);

            $tongTien = max(0, $tongTienGoc - $tienGiam + $phiVanChuyen);

            $paymentMethod = $request->payment_method;
            $isOnline = ($paymentMethod === 'ONLINE');

            $donHangData = [
                'id_nguoi_dung' => $idNguoiDung,
                'ten' => $request->name,
                'sdt' => $request->sdt,
                'email' => $request->email,
                'dia_chi' => $request->address,
                'tong_tien' => $tongTien,
                'phi_van_chuyen' => $phiVanChuyen,
                'tien_giam' => $tienGiam,
                'ma_khuyen_mai' => $maKhuyenMai,
                'ngay_dat' => now(),
                'trang_thai' => 'Đang chờ duyệt',
                'trang_thai_thanh_toan' => $isOnline ? 'Chờ xác nhận tiền' : 'Chưa thanh toán',
                'pt_thanh_toan' => $paymentMethod,
                'ma_chuyen_khoan' => $isOnline ? $request->ma_chuyen_khoan : null,
                'het_han_thanh_toan' => $isOnline ? now()->addMinutes(30) : null,
                'latitude' => $latitude,
                'longitude' => $longitude,
            ];

            $donHang = DonHang::create($donHangData);

            foreach ($orderItems as $item) {
                $sanPham = $item['san_pham'];
                $soLuongDat = $item['so_luong'];

                $ketQuaXuatKho = $sanPham->xuatKhoTheoFEFO($soLuongDat);                
                foreach ($ketQuaXuatKho as $loXuat) {
                    CtDonHang::create([
                        'id_don_hang' => $donHang->id,
                        'id_san_pham' => $sanPham->id,
                        'ma_lo'       => $loXuat['ma_lo'] ?? null,
                        'so_luong'    => $loXuat['so_luong'],
                        'gia'         => $item['gia'],
                    ]);
                }
            }

            if ($checkoutType === 'buy_now') {
                session()->forget('buy_now');
            } else {
                if (isset($gioHang)) {
                    $gioHang->ctGioHangs()->delete();
                    $gioHang->update(['so_luong_sp' => 0]);
                }
            }

            DB::commit();

            // Gửi mail xác nhận
            try {
                $donHang->load('ctDonHangs.sanPham');
                Mail::to($donHang->email)->send(new OrderSuccessMail($donHang));
            } catch (\Exception $e) {
                logger("Gửi mail xác nhận đơn thất bại: " . $e->getMessage());
            }

            $successMsg = ($paymentMethod === 'ONLINE')
                ? 'Đặt hàng thành công! Vui lòng chuyển khoản trong vòng 30 phút.'
                : 'Đặt hàng thành công! Đơn hàng của bạn đã được ghi nhận.';

            return redirect()->route('client.donHangCuaToi')->with('success', $successMsg);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Đặt hàng thất bại: ' . $e->getMessage());
        }
    }

    // 3. DANH SÁCH ĐƠN HÀNG CỦA TÔI
    public function donHangCuaToi(Request $request)
    {
        if (!session()->has('idNguoiDung')) {
            return redirect()->route('client.dangNhap')->with('errorLogin', 'Vui lòng đăng nhập!');
        }

        $idNguoiDung = session('idNguoiDung');

        $query = DonHang::where('id_nguoi_dung', $idNguoiDung)
            ->with('ctDonHangs.sanPham')
            ->orderBy('ngay_dat', 'desc');

        if ($request->filled('status')) {
            $query->where('trang_thai', $request->status);
        }

        $donHangs = $query->get();

        return view('client.donHang', compact('donHangs'));
    }

    // 4. CHI TIẾT ĐƠN HÀNG
    public function chiTietDonHang($id)
    {
        if (!session()->has('idNguoiDung')) {
            return redirect()->route('client.dangNhap')->with('errorLogin', 'Vui lòng đăng nhập!');
        }

        $idNguoiDung = session('idNguoiDung');

        $donHang = DonHang::where('id_nguoi_dung', $idNguoiDung)
            ->with('ctDonHangs.sanPham')
            ->findOrFail($id);

        return view('client.chiTietDonHang', compact('donHang'));
    }
    
    
    public function huyDonHang($id)
    {
        if (!session()->has('idNguoiDung')) {
            return redirect()->route('client.dangNhap')->with('errorLogin', 'Vui lòng đăng nhập!');
        }

        $idNguoiDung = session('idNguoiDung');
        $donHang = DonHang::where('id_nguoi_dung', $idNguoiDung)
            ->findOrFail($id);

        if ($donHang->trang_thai != 'Đang chờ duyệt') {
            return redirect()->route('client.donHangCuaToi')->with('error', 'Không thể hủy đơn hàng này!');
        }

        DB::transaction(function () use ($id, $donHang) {
            $chiTiet = CtDonHang::where('id_don_hang', $id)->get();
            
            foreach ($chiTiet as $item) {
                LoHang::where('ma_lo', $item->ma_lo)
                    ->increment('so_luong_con_lai', $item->so_luong);
                SanPham::find($item->id_san_pham)->increment('so_luong', $item->so_luong);
            }
            

            if (!empty($donHang->ma_khuyen_mai)) {
                KhuyenMai::where('ma_khuyen_mai', $donHang->ma_khuyen_mai)
                    ->where('da_dung', '>', 0)
                    ->decrement('da_dung');
            }

            $donHang->update([
                'trang_thai' => 'Đã hủy',
            ]);
        });

        return redirect()->route('client.donHangCuaToi')->with('success', 'Hủy đơn hàng thành công!');
    }

    public function yeuCauTraHang(Request $request, $id){
        if (!session()->has('idNguoiDung')) {
            return redirect()->route('client.dangNhap')->with('errorLogin', 'Vui lòng đăng nhập!');
        }

        $idNguoiDung = session('idNguoiDung');
        $donHang = DonHang::where('id_nguoi_dung', $idNguoiDung)->findOrFail($id);

        if ($donHang->trang_thai !== 'Đã nhận hàng') {
            return redirect()->route('client.chiTietDonHang', $id)->with('error', 'Chỉ có thể yêu cầu trả hàng khi đơn hàng ở trạng thái "Đã nhận hàng"!');
        }

        if ($donHang->het_han_tra_hang && now() > $donHang->het_han_tra_hang) {
            return redirect()->route('client.chiTietDonHang', $id)->with('error', 'Đã quá thời hạn 1 ngày cho phép đổi trả sản phẩm!');
        }

        $request->validate([
            'ly_do' => 'required|string|min:10|max:1000',
            'hinh_anh_tra' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'ly_do.required' => 'Vui lòng nhập lý do từ 10 đến 1000 ký tự.',
            'ly_do.min' => 'Vui lòng nhập lý do từ 10 đến 1000 ký tự.',
            'ly_do.max' => 'Lý do trả hàng không được vượt quá 1000 ký tự.',
            'hinh_anh_tra.required' => 'Vui lòng tải lên hình ảnh minh chứng sản phẩm lỗi/hỏng.',
            'hinh_anh_tra.image' => 'Tệp tải lên phải là hình ảnh.',
            'hinh_anh_tra.mimes' => 'Ảnh minh chứng phải có định dạng: jpeg, png, jpg, webp.',
            'hinh_anh_tra.max' => 'Kích thước ảnh minh chứng không được vượt quá 2MB.',
        ]);

        $tenAnh = null;
        if ($request->hasFile('hinh_anh_tra')) {
            $file = $request->file('hinh_anh_tra');
            $tenAnh = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/tra-hang'), $tenAnh);
        }

        $donHang->update([
            'trang_thai'          => 'Yêu cầu trả hàng',
            'ly_do_tra_hang'      => $request->input('ly_do'),
            'hinh_anh_tra'        => $tenAnh ? 'uploads/tra-hang/' . $tenAnh : null,
            'ly_do_tu_choi_tra'   => null,
        ]);

        return redirect()->route('client.chiTietDonHang', $id)->with('success', 'Yêu cầu trả hàng đã được gửi thành công!');
    }    
}