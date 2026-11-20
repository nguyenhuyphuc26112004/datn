<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DonHang;
use App\Models\CtDonHang;
use App\Models\SanPham;
use App\Models\LoHang;
use App\Models\KhuyenMai;
use App\Models\LichSuThanhToan;
use Illuminate\Support\Carbon;
class DonHangController extends Controller
{
    private function refundStockAndPromotion(DonHang $donHang)
    {
        $ctDonHang = CtDonHang::where('id_don_hang', $donHang->id)->get();
        
        foreach ($ctDonHang as $item) {
            LoHang::where('ma_lo', $item->ma_lo)
                ->increment('so_luong_con_lai', $item->so_luong);

            SanPham::where('id', $item->id_san_pham)
            ->increment('so_luong', $item->so_luong);
        }

        if (!empty($donHang->ma_khuyen_mai) && (float) $donHang->tien_giam > 0) {
            KhuyenMai::where('ma_khuyen_mai', $donHang->ma_khuyen_mai)
                ->where('da_dung', '>', 0)
                ->decrement('da_dung');
        }
    }

    public function index(Request $request)
    {
        $now = Carbon::now();        
        $donQuaHan = DonHang::whereHas('lichSuThanhToan', function ($query) use ($now) {
            $query->where('pt_thanh_toan', 'ONLINE')
                ->where('trang_thai_thanh_toan', 'Chờ xác nhận tiền')
                ->where('thoi_gian_het_han', '<', $now);
        })
            ->with('lichSuThanhToan')
            ->get();

        if ($donQuaHan->isNotEmpty()) {
            foreach ($donQuaHan as $order) {
                DB::transaction(function () use ($order) {
                    $this->refundStockAndPromotion($order);

                    $order->update([
                        'trang_thai' => 'Đã hủy',
                    ]);
                    LichSuThanhToan::where('id_don_hang', $order->id)
                        ->update([
                            'trang_thai_thanh_toan' => 'Quá hạn thanh toán',
                        ]);
                });
            }
        }

        $query = DonHang::query();
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('id', 'LIKE', "%{$searchTerm}%")
                ->orWhere('ten', 'LIKE', "%{$searchTerm}%")
                ->orWhere('sdt', 'LIKE', "%{$searchTerm}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('trang_thai', $request->status);
        }

        $donHang = $query->with('lichSuThanhToan')->orderBy('id', 'desc')->paginate(15)->withQueryString();
        $choDuyet = DonHang::where('trang_thai', 'Đang chờ duyệt')->count();
        $soTraHang = DonHang::where('trang_thai', 'Yêu cầu trả hàng')->count();

        return view('admin.donhang.quanLyDH', compact('donHang', 'choDuyet', 'soTraHang'));
    }

    public function show($id)
    {
        $donHang = DonHang::with('lichSuThanhToan')->findOrFail($id);

        $ctDonHang = CtDonHang::join('san_pham', 'ct_don_hang.id_san_pham', '=', 'san_pham.id')
            ->where('ct_don_hang.id_don_hang', $id)
            ->select(
                'ct_don_hang.*',
                'san_pham.ten as ten_san_pham',
                'san_pham.anh as anh_san_pham'
            )->get();

        return view('admin.donhang.xemCT_DH', compact('donHang', 'ctDonHang'));
    }

    public function edit($id)
    {
        $donHang = DonHang::with('lichSuThanhToan')->findOrFail($id);
        return view('admin.donhang.capNhat_DH', compact('donHang'));
    }

    public function update(Request $request, $id){
        $donHang = DonHang::findOrFail($id);
        $request->validate([
            'diaChi' => 'required|string|max:255',
            'sdt' => ['required', 'string', 'regex:/^(0[0-9]{9}|84[0-9]{9,10})$/'],
            'trangThai' => 'required|string',
            'trangThaiThanhToan' => 'required|string',
        ], [
            'diaChi.required' => 'Địa chỉ giao hàng không được để trống.',
            'diaChi.max' => 'Địa chỉ quá dài, vui lòng nhập dưới 255 ký tự.',
            'sdt.required' => 'Số điện thoại không được để trống.',
            'sdt.regex' => 'Số điện thoại không hợp lệ.',
        ]);

        $diaChi = $request->input('diaChi');
        $sdt = $request->input('sdt');
        $trangThaiMoi = $request->input('trangThai');
        $trangThaiThanhToan = $request->input('trangThaiThanhToan');
        $lichSu = $donHang->lichSuThanhToan()->first();

        if (
            $donHang->trang_thai !== 'Đã hủy'
            && $trangThaiMoi === 'Đã hủy'
            && $lichSu
            && $lichSu->pt_thanh_toan === 'ONLINE'
            && $lichSu->trang_thai_thanh_toan === 'Đã thanh toán'
        ) {
            return back()
                ->withInput()
                ->with('error', 'Không thể hủy đơn hàng đã thanh toán online.');
        }

        if ($donHang->trang_thai !== 'Đã hủy' && $trangThaiMoi === 'Đã hủy') {
            DB::transaction(function () use ($donHang) {
                $this->refundStockAndPromotion($donHang);
            });
        }

        if ($trangThaiMoi === 'Đã hủy' && $trangThaiThanhToan !== 'Đã thanh toán') {
            $trangThaiThanhToan = 'Đã hủy';
        }

        $thongTinDonHang = [
            'dia_chi' => $diaChi,
            'sdt' => $sdt,
            'trang_thai' => $trangThaiMoi,
        ];

        if ($donHang->trang_thai !== 'Đã nhận hàng' && $trangThaiMoi === 'Đã nhận hàng') {
            $trangThaiThanhToan = 'Đã thanh toán';
            $thongTinDonHang['het_han_tra_hang'] = now()->addDays(1);
        }

        $donHang->update($thongTinDonHang);

        if ($lichSu) {
            $lichSu->update([
                'trang_thai_thanh_toan' => $trangThaiThanhToan,
                'thoi_gian_thanh_toan' => $trangThaiThanhToan === 'Đã thanh toán'
                    ? ($lichSu->thoi_gian_thanh_toan ?? now())
                    : null,
            ]);
        } 
        
        return redirect()->route('admin.donhang.index', $request->query())
            ->with('success', 'Cập nhật đơn hàng thành công!');
    }


    public function inHoaDon($id)
    {
        $donHang = DonHang::with('lichSuThanhToan')->findOrFail($id);

        $chiTiet = CtDonHang::join('san_pham', 'ct_don_hang.id_san_pham', '=', 'san_pham.id')
            ->where('ct_don_hang.id_don_hang', $id)
            ->select(
                'ct_don_hang.*', 
                'san_pham.ten as ten_san_pham', 
                'san_pham.don_vi'
            )->get();

        return view('admin.donhang.in_hoa_don', compact('donHang', 'chiTiet'));
    }

    public function confirmDelete($id)
    {
        $donHang = DonHang::findOrFail($id);
        return view('admin.donhang.xoa_DH', compact('donHang'));
    }

    public function destroy(Request $request, $id)
    {
        $donHang = DonHang::findOrFail($id);
        
        if ($donHang->trang_thai !== 'Đã hủy') {
            return redirect()->route('admin.donhang.index', $request->query())
                ->with('error', 'Chỉ có thể xóa đơn hàng đã bị hủy!');
        }

        DB::transaction(function () use ($id, $donHang) {
            CtDonHang::where('id_don_hang', $id)->delete();
            $donHang->delete();
        });

        return redirect()->route('admin.donhang.index', $request->query())
            ->with('success', 'Xóa đơn hàng #' . $id . ' thành công!');
    }

    public function showXuLyTraHang(Request $request, $id)
    {
        $donHang = DonHang::with('lichSuThanhToan')->findOrFail($id);

        if ($donHang->trang_thai !== 'Yêu cầu trả hàng') {
            return redirect()->route('admin.donhang.index', $request->query())
                ->with('error', 'Đơn hàng này không có yêu cầu trả hàng!');
        }

        $chiTiet = CtDonHang::join('san_pham', 'ct_don_hang.id_san_pham', '=', 'san_pham.id')
            ->where('ct_don_hang.id_don_hang', $id)
            ->select(
                'ct_don_hang.*', 
                'san_pham.ten as ten_san_pham', 
                'san_pham.anh as anh_san_pham')
            ->get();

        return view('admin.donhang.xuLyTraHang', compact('donHang', 'chiTiet'));
    }

    public function xuLyTraHang(Request $request, $id)
    {
        $donHang = DonHang::findOrFail($id);

        if ($donHang->trang_thai !== 'Yêu cầu trả hàng') {
            return redirect()->route('admin.donhang.index', $request->query())
                ->with('error', 'Đơn hàng này không ở trạng thái yêu cầu trả hàng!');
        }

        $hanhDong = $request->input('hanh_dong');

        if ($hanhDong === 'duyệt') {
            DB::transaction(function () use ($id, $donHang) {
                $this->refundStockAndPromotion($donHang);

                $donHang->update([
                    'trang_thai' => 'Đã trả hàng',
                    'ly_do_tu_choi_tra' => null,
                ]);
            });

            return redirect()->route('admin.donhang.index', $request->query())
                ->with('success', "Đã duyệt trả hàng đơn #{$id}. Tồn kho đã được hoàn trả chính xác về các lô!");

        } else {
            $request->validate([
                'ly_do_tu_choi' => 'required|string|min:15|max:500',
            ], [
                'ly_do_tu_choi.required' => 'Vui lòng nhập lý do từ chối.',
                'ly_do_tu_choi.min' => 'Lý do từ chối phải có ít nhất 15 ký tự.',
                'ly_do_tu_choi.max' => 'Lý do từ chối không được vượt quá 500 ký tự.'
            ]);

            $donHang->update([
                'trang_thai' => 'Từ chối trả hàng',
                'ly_do_tu_choi_tra'  => $request->input('ly_do_tu_choi'),
            ]);

            return redirect()->route('admin.donhang.index', $request->query())
                ->with('success', "Đã từ chối yêu cầu trả hàng đơn #{$id}!");
        }
    }
}