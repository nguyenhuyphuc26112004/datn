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
use App\Models\LichSuThanhToan;      
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
            $km = $this->calculateDistance($this->shopLat, $this->shopLng, (float)$lat, (float)$lng
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
                    'so_luong' => $item['so_luong'] ?? 1,
                    'gia' => (float) $item['gia'],
                    'ten' => $item['ten'] ?? 'Sản phẩm',
                    'anh' => $item['anh'] ?? 'default.jpg',
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
                if (Carbon::parse($sanPham->ngay_het_han)->lt(Carbon::today())) {
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

                $cTGioHang[] = [
                    'id_san_pham' => $ct->id_san_pham,
                    'so_luong' => $ct->so_luong,
                    'gia' => (float) $sanPham->gia,
                    'ten' => $sanPham->ten,
                    'anh' => $sanPham->anh,
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

        $latUser = $request->input('latitude');
        $lngUser = $request->input('longitude');
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
            'name' => 'required|string|max:255',
            'sdt' => ['required', 'string', 'regex:/^(0[0-9]{9}|84[0-9]{9,10})$/'],            
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
        ], [
            'name.required' => 'Vui lòng không để trống họ tên.',
            'sdt.required' => 'Vui lòng không để trống số điện thoại.',
            'sdt.regex' => 'Số điện thoại không hợp lệ.',
            'email.required' => 'Vui lòng không để trống email.',
            'email.email' => 'Email không đúng định dạng (VD: example@gmail.com).',
        ]);

        $checkoutType = $request->input('checkout_type', 'cart');
        $rawOrderItems = [];

        if ($checkoutType === 'buy_now' && session()->has('buy_now')) {
            $buyNow = session('buy_now');
            foreach ($buyNow as $idSp => $item) {
                $rawOrderItems[] = [
                    'id_san_pham' => $idSp,
                    'so_luong' => $item['so_luong'],
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
                    'so_luong' => $ct->so_luong,
                ];
            }
        }

        DB::beginTransaction();
        try {
            $orderItems  = [];
            $tongTienGoc = 0;
            $cartErrors  = [];

            foreach ($rawOrderItems as $raw) {
                $sanPham = SanPham::where('id', $raw['id_san_pham'])->lockForUpdate()->first();
                if (!$sanPham) {
                    $cartErrors[] = "Sản phẩm không còn tồn tại!";
                    continue;
                }
                if($sanPham->so_luong == 0){
                    $cartErrors[] = "Sản phẩm '{$sanPham->ten}' vừa hết hàng!";
                    continue;
                }
                if ($sanPham->trang_thai == 0) {
                    $cartErrors[] = "Sản phẩm '{$sanPham->ten}' hiện đã ngừng kinh doanh!";
                    continue;
                }
                if (Carbon::parse($sanPham->ngay_het_han)->lt(Carbon::today())) {
                    $cartErrors[] = "Sản phẩm '{$sanPham->ten}' vừa hết hạn sử dụng!";
                    continue;
                }

                $tongKhaDung = $sanPham->loHangsKhaDung()->sum('so_luong_con_lai');
                if ($tongKhaDung < $raw['so_luong']) {
                    $cartErrors[] = "Sản phẩm '{$sanPham->ten}' không đủ tồn kho (chỉ còn {$tongKhaDung} sản phẩm trong hạn, bạn đang đặt {$raw['so_luong']})!";
                    continue;
                }
                $orderItems[] = [
                    'san_pham' => $sanPham,
                    'so_luong' => $raw['so_luong'],
                ];

                $tongTienGoc += (float) $sanPham->gia * $raw['so_luong'];
            }

            if (!empty($cartErrors)) {
                DB::rollBack();
                if ($checkoutType === 'buy_now') {
                    $idSp = array_key_first(session('buy_now', []));
                    return redirect()->route('client.product.detail', $idSp)->with('error', implode(' ', $cartErrors)); 
                }
                return redirect()->route('client.gioHang')->with('errorCart', $cartErrors);
            }
            $latUser = $request->input('latitude');
            $lngUser = $request->input('longitude');
            $diaChiUser = $request->input('address');
            $phiVanChuyen = $this->tinhPhiVanChuyen($tongTienGoc, $latUser, $lngUser, $diaChiUser);

            $tienGiam = 0;
            $maKhuyenMai = trim($request->input('ma_khuyen_mai'));
            if (!empty($maKhuyenMai)) {
                $khuyenMai = KhuyenMai::where('ma_khuyen_mai', $maKhuyenMai)
                    ->lockForUpdate()
                    ->first();

                if (!$khuyenMai) {
                    DB::rollBack();
                    return redirect()->back()->withInput()->with('error', 'Mã khuyến mãi không còn tồn tại. Vui lòng kiểm tra lại đơn hàng.');
                }

                $loiKhuyenMai = $khuyenMai->kiemTraHopLe($idNguoiDung, $tongTienGoc);
                if ($loiKhuyenMai) {
                    DB::rollBack();
                    return redirect()->back()->withInput()->with('error', $loiKhuyenMai);
                }

                $tienGiam = $khuyenMai->tinhTienGiam($tongTienGoc);
                $khuyenMai->increment('da_dung');
            }
            
            $tongTien = max(0, $tongTienGoc - $tienGiam + $phiVanChuyen);

            $paymentMethod = $request->payment_method;
            $isOnline = ($paymentMethod === 'ONLINE');
            $thoiGianHetHanThanhToan = $isOnline ? now()->addMinutes(30) : null;

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
                'latitude' => $latUser,
                'longitude' => $lngUser,
            ];

            $donHang = DonHang::create($donHangData);
            LichSuThanhToan::create([
                'id_don_hang' => $donHang->id,
                'pt_thanh_toan' => $paymentMethod,
                'ma_chuyen_khoan' => $isOnline ? $request->ma_chuyen_khoan : null,
                'so_tien' => $tongTien,
                'trang_thai_thanh_toan' => $isOnline ? 'Chờ xác nhận tiền' : 'Chưa thanh toán',
                'thoi_gian_thanh_toan' => null,
                'thoi_gian_het_han' => $thoiGianHetHanThanhToan,
                'du_lieu_phan_hoi' => null,
            ]);
            foreach ($orderItems as $item) {
                $sanPham = $item['san_pham'];
                $soLuongDat = $item['so_luong'];

                $ketQuaXuatKho = $sanPham->xuatKhoTheoFEFO($soLuongDat);                
                foreach ($ketQuaXuatKho as $loXuat) {
                    CtDonHang::create([
                        'id_don_hang' => $donHang->id,
                        'id_san_pham' => $sanPham->id,
                        'ma_lo'       => $loXuat['ma_lo'],
                        'so_luong'    => $loXuat['so_luong'],
                        'gia'         => $sanPham->gia,
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

            try {
                $donHang->load('ctDonHangs.sanPham', 'lichSuThanhToan');
                Mail::to($donHang->email)->send(new OrderSuccessMail($donHang));
            } catch (\Exception $e) {
                logger("Gửi mail xác nhận đơn thất bại: " . $e->getMessage());
            }

            $successMsg = ($paymentMethod === 'ONLINE')
                ? 'Đặt hàng thành công! Vui lòng chuyển khoản trong vòng 30 phút.'
                : 'Đặt hàng thành công! Đơn hàng của bạn đã được ghi nhận.';

            return redirect()->route('client.chiTietDonHang', $donHang->id)->with('success', $successMsg);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Đặt hàng thất bại: ' . $e->getMessage());
        }
    }

    public function donHangCuaToi(Request $request)
    {
        if (!session()->has('idNguoiDung')) {
            return redirect()->route('client.dangNhap')->with('errorLogin', 'Vui lòng đăng nhập!');
        }

        $idNguoiDung = session('idNguoiDung');

        $query = DonHang::where('id_nguoi_dung', $idNguoiDung)
            ->with(['ctDonHangs.sanPham', 'lichSuThanhToan'])
            ->orderBy('ngay_dat', 'desc');

        if ($request->filled('status')) {
            $query->where('trang_thai', $request->status);
        }

        $donHangs = $query->get();

        return view('client.donHang', compact('donHangs'));
    }

    public function chiTietDonHang($id)
    {
        if (!session()->has('idNguoiDung')) {
            return redirect()->route('client.dangNhap')->with('errorLogin', 'Vui lòng đăng nhập!');
        }

        $idNguoiDung = session('idNguoiDung');

        $donHang = DonHang::where('id_nguoi_dung', $idNguoiDung)
            ->with(['ctDonHangs.sanPham', 'lichSuThanhToan'])
            ->findOrFail($id);

        return view('client.chiTietDonHang', compact('donHang'));
    }
    
    
    public function huyDonHang(Request $request, $id)
    {
        if (!session()->has('idNguoiDung')) {
            return redirect()->route('client.dangNhap')->with('errorLogin', 'Vui lòng đăng nhập!');
        }

        $idNguoiDung = session('idNguoiDung');
        $donHang = DonHang::where('id_nguoi_dung', $idNguoiDung)
            ->findOrFail($id);

        if ($donHang->trang_thai != 'Đang chờ duyệt') {
            return redirect()->route('client.chiTietDonHang', ['id' => $id] + $request->query())
                ->with('error', 'Không thể hủy đơn hàng này!');
        }

        $cancelled = DB::transaction(function () use ($id, $idNguoiDung) {
            $donHang = DonHang::where('id', $id)
                ->where('id_nguoi_dung', $idNguoiDung)
                ->lockForUpdate()->firstOrFail();

            if ($donHang->trang_thai !== 'Đang chờ duyệt') {
                return false;
            }

            $lichSu = LichSuThanhToan::where('id_don_hang', $donHang->id)
                ->lockForUpdate()->first();
            if ($lichSu && $lichSu->trang_thai_thanh_toan === 'Đã thanh toán') {
                return 'paid';
            }

            $chiTiet = CtDonHang::where('id_don_hang', $donHang->id)
                ->lockForUpdate()->get();
            
            foreach ($chiTiet as $item) {
                $loHang = LoHang::where('ma_lo', $item->ma_lo)
                    ->lockForUpdate()
                    ->firstOrFail();
                $loHang->increment('so_luong_con_lai', $item->so_luong);

                $sanPham = SanPham::whereKey($item->id_san_pham)
                    ->lockForUpdate()
                    ->firstOrFail();
                $sanPham->increment('so_luong', $item->so_luong);
            }
            
            if (!empty($donHang->ma_khuyen_mai)) {
                $khuyenMai = KhuyenMai::where('ma_khuyen_mai', $donHang->ma_khuyen_mai)
                    ->lockForUpdate()
                    ->first();
                if ($khuyenMai && $khuyenMai->da_dung > 0) {
                    $khuyenMai->decrement('da_dung');
                }
            }

            $donHang->update([
                'trang_thai' => 'Đã hủy',
            ]);
            
            if ($lichSu && in_array($lichSu->trang_thai_thanh_toan, ['Chờ xác nhận tiền', 'Chưa thanh toán'], true)) {
                $lichSu->update(['trang_thai_thanh_toan' => 'Đã hủy']);
            }

            return true;
        });

        if ($cancelled === 'paid') {
            return redirect()->route('client.chiTietDonHang', ['id' => $id] + $request->query())
                ->with('error', 'Đơn hàng đã thanh toán nên không thể hủy. Vui lòng liên hệ cửa hàng để được hỗ trợ.');
        }

        if (!$cancelled) {
            return redirect()->route('client.chiTietDonHang', ['id' => $id] + $request->query())
                ->with('error', 'Không thể hủy đơn hàng này!');
        }

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
            'hinh_anh_tra.mimes' => 'Ảnh minh chứng phải có định dạng: jpeg, png, jpg',
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