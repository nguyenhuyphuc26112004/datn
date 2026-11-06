<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DonHang;
use App\Models\CtDonHang;
use App\Models\Sanpham;
use App\Models\LoHang;

class DonHangController extends Controller
{
    // Danh sách đơn hàng
    public function index(Request $request)
    {
        $query = DonHang::query();

        // Tìm kiếm
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('id', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('ten', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('sdt', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Lọc trạng thái
        if ($request->filled('status')) {
            $query->where('trang_thai', $request->status);
        }

        $donHang = $query->orderBy('id', 'desc')->paginate(15);

        return view('admin.donhang.quanLyDH', compact('donHang'));
    }

    public function show($id)
    {
        $donHang = DonHang::findOrFail($id);

        $ctDonHang = CtDonHang::join('san_pham', 'ct_don_hang.id_san_pham', '=', 'san_pham.id')
            ->where('ct_don_hang.id_don_hang', $id)
            ->select(
                'ct_don_hang.*',
                'san_pham.ten as ten_san_pham',
                'san_pham.anh as anh_san_pham'
            )->get();

        return view('admin.donhang.xemCT_DH', compact('donHang', 'ctDonHang'));
    }

    
    private function refundStock($id)
    {
        $ctDonHang = CtDonHang::where('id_don_hang', $id)->get();
        
        foreach ($ctDonHang as $item) {
            LoHang::where('ma_lo', $item->ma_lo)
                ->increment('so_luong_con_lai', $item->so_luong);

            SanPham::where('id', $item->id_san_pham)
            ->increment('so_luong', $item->so_luong);
        }
    }

    public function edit($id)
    {
        $donHang = DonHang::findOrFail($id);
        return view('admin.donhang.capNhat_DH', compact('donHang'));
    }

    // Cập nhật đơn hàng
    public function update(Request $request, $id){
        $donHang = DonHang::findOrFail($id);
        $request->validate([
            'diaChi'              => 'required|string|max:255',
            'sdt'                 => 'required|string|max:11',
            'trangThai'           => 'required|string',
            'trangThaiThanhToan'  => 'required|string',
        ], [
            'diaChi.required'     => 'Địa chỉ giao hàng không được để trống.',
            'diaChi.max'          => 'Địa chỉ quá dài, vui lòng nhập dưới 255 ký tự.',
            'sdt.required'        => 'Số điện thoại không được để trống.',
            'sdt.max'             => 'Số điện thoại không được vượt quá 11 ký tự.',
        ]);

        $diaChi = $request->input('diaChi');
        $sdt = $request->input('sdt');
        $trangThaiMoi = $request->input('trangThai');
        $trangThaiThanhToan = $request->input('trangThaiThanhToan');

        if ($donHang->trang_thai !== 'Đã nhận hàng' && $trangThaiMoi === 'Đã nhận hàng') {
            $trangThaiThanhToan = 'Đã thanh toán';
            $donHang->het_han_tra_hang = now()->addDays(1);
        }

        if ($donHang->trang_thai !== 'Đã hủy' && $trangThaiMoi === 'Đã hủy') {
            DB::transaction(function () use ($id, $donHang) {
                $this->refundStock($id);

                if (!empty($donHang->ma_khuyen_mai) && (float)$donHang->tien_giam > 0) {
                    DB::table('khuyen_mai')
                        ->where('ma_khuyen_mai', $donHang->ma_khuyen_mai)
                        ->where('da_dung', '>', 0)
                        ->decrement('da_dung');
                }
            });
        }

        if ($donHang->trang_thai !== 'Đã trả hàng' && $trangThaiMoi === 'Đã trả hàng') {
            DB::transaction(function () use ($id) {
                $this->refundStock($id);
            });
        }

        $donHang->dia_chi = $diaChi;
        $donHang->sdt = $sdt;
        $donHang->trang_thai = $trangThaiMoi;
        $donHang->trang_thai_thanh_toan = $trangThaiThanhToan;
        $donHang->save();

        return redirect()->route('admin.donhang.index')->with('success', 'Cập nhật đơn hàng thành công!');
    }

    
    public function xacNhanTien($id)
    {
        $donHang = DonHang::where('id', $id)->first();
        if (!$donHang) {
            return redirect()->back()->with('error', 'Đơn hàng không tồn tại!');
        }

        $donHang->update([
            'trang_thai_thanh_toan' => 'Đã thanh toán',
            'trang_thai' => ($donHang->trang_thai === 'Đang chờ duyệt') ? 'Đã xác nhận' : $donHang->trang_thai,
            'het_han_thanh_toan' => null,
        ]);

        return redirect()->back()->with('success', "Đã xác nhận thanh toán đơn hàng #{$id} thành công!");
    }

    public function inHoaDon($id)
    {
        $donHang = DonHang::findOrFail($id);

        $chiTiet = DB::table('ct_don_hang')
            ->join('san_pham', 'ct_don_hang.id_san_pham', '=', 'san_pham.id')
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

    public function destroy($id)
    {
        $donHang = DonHang::findOrFail($id);
        
        if ($donHang->trang_thai !== 'Đã hủy') {
            return redirect()->route('admin.donhang.index')
                ->with('error', 'Chỉ có thể xóa đơn hàng đã bị hủy!');
        }

        DB::transaction(function () use ($id, $donHang) {
            DB::table('ct_don_hang')->where('id_don_hang', $id)->delete();
            $donHang->delete();
        });

        return redirect()->route('admin.donhang.index')
            ->with('success', 'Xóa đơn hàng #' . $id . ' thành công!');
    }

    public function showXuLyTraHang($id)
    {
        $donHang = DonHang::findOrFail($id);

        if ($donHang->trang_thai !== 'Yêu cầu trả hàng') {
            return redirect()->route('admin.donhang.index')->with('error', 'Đơn hàng này không có yêu cầu trả hàng!');
        }

        $chiTiet = DB::table('ct_don_hang')
            ->join('san_pham', 'ct_don_hang.id_san_pham', '=', 'san_pham.id')
            ->where('ct_don_hang.id_don_hang', $id)
            ->select('ct_don_hang.*', 'san_pham.ten as ten_san_pham', 'san_pham.anh as anh_san_pham')
            ->get();

        return view('admin.donhang.xuLyTraHang', compact('donHang', 'chiTiet'));
    }

    public function xuLyTraHang(Request $request, $id)
    {
        $donHang = DonHang::findOrFail($id);

        if ($donHang->trang_thai !== 'Yêu cầu trả hàng') {
            return redirect()->route('admin.donhang.index')->with('error', 'Đơn hàng này không ở trạng thái yêu cầu trả hàng!');
        }

        $hanh_dong = $request->input('hanh_dong');

        if ($hanh_dong === 'duyet') {
            DB::transaction(function () use ($id, $donHang) {
                $this->refundStock($id);

                if (!empty($donHang->ma_khuyen_mai) && (float)$donHang->tien_giam > 0) {
                    DB::table('khuyen_mai')
                        ->where('ma_khuyen_mai', $donHang->ma_khuyen_mai)
                        ->where('da_dung', '>', 0)
                        ->decrement('da_dung');
                }

                $donHang->update([
                    'trang_thai'        => 'Đã trả hàng',
                    'ly_do_tu_choi_tra' => null,
                ]);
            });

            return redirect()->route('admin.donhang.index')
                ->with('success', "Đã duyệt trả hàng đơn #{$id}. Tồn kho đã được hoàn trả chính xác về các lô!");

        } else {
            $request->validate([
                'ly_do_tu_choi' => 'required|string|min:15|max:500',
            ], [
                'ly_do_tu_choi.required' => 'Vui lòng nhập lý do từ chối.',
                'ly_do_tu_choi.min'      => 'Lý do từ chối phải có ít nhất 15 ký tự.',
                'ly_do_tu_choi.max'      => 'Lý do từ chối không được vượt quá 500 ký tự.'
            ]);

            $donHang->update([
                'trang_thai'         => 'Từ chối trả hàng',
                'ly_do_tu_choi_tra'  => $request->input('ly_do_tu_choi'),
            ]);

            return redirect()->route('admin.donhang.index')
                ->with('success', "Đã từ chối yêu cầu trả hàng đơn #{$id}!");
        }
    }
}