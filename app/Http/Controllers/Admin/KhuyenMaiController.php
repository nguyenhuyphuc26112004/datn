<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhuyenMai;
use App\Models\DonHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KhuyenMaiController extends Controller
{
    public function index(Request $request)
    {
        $query = KhuyenMai::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('ma_khuyen_mai', 'LIKE', "%{$s}%")
                  ->orWhere('ten_chuong_trinh', 'LIKE', "%{$s}%");
            });
        }

        $khuyenMais = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.khuyenmai.quanLyKM', compact('khuyenMais'));
    }

    public function create()
    {
        $lastKhuyenMai = KhuyenMai::orderBy('id', 'desc')->first();

        $nextId = $lastKhuyenMai ? $lastKhuyenMai->id + 1 : 1;

        $autoMaCode = 'KM_' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        return view('admin.khuyenmai.them_KM', compact('autoMaCode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ma_khuyen_mai' => 'required|string|max:50|unique:khuyen_mai,ma_khuyen_mai',
            'ten_chuong_trinh' => 'required|string|max:255',
            'loai_giam' => 'required|in:phan_tram,tien_mat',
            'gia_tri_giam' => 'required|numeric|gt:0',
            'giam_toi_da' => 'nullable|numeric|gt:0',
            'don_hang_toi_thieu' => 'nullable|numeric|min:0',
            'so_luong' => 'required|integer|gt:0',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after:ngay_bat_dau',
        ], [
            'ma_khuyen_mai.required' => 'Vui lòng nhập mã khuyến mãi.',
            'ma_khuyen_mai.unique' => 'Mã khuyến mãi đã tồn tại.',
            'ten_chuong_trinh.required' => 'Vui lòng nhập tên chương trình.',
            'gia_tri_giam.required' => 'Vui lòng nhập giá trị giảm.',
            'gia_tri_giam.gt' => 'Giá trị giảm phải lớn hơn 0.',
            'so_luong.required' => 'Vui lòng nhập số lượng mã.',
            'ngay_ket_thuc.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
        ]);

        if ($request->loai_giam === 'phan_tram' && $request->gia_tri_giam > 100) {
            return back()->withInput()->withErrors(['gia_tri_giam' => 'Giảm phần trăm không được vượt quá 100%.']);
        }

        KhuyenMai::create([
            'ma_khuyen_mai' => strtoupper(trim($request->ma_khuyen_mai)),
            'ten_chuong_trinh' => trim($request->ten_chuong_trinh),
            'loai_giam' => $request->loai_giam,
            'gia_tri_giam' => $request->gia_tri_giam,
            'giam_toi_da' => $request->giam_toi_da,
            'don_hang_toi_thieu' => $request->don_hang_toi_thieu ?? 0,
            'so_luong' => $request->so_luong,
            'da_dung' => 0,
            'ngay_bat_dau' => $request->ngay_bat_dau,
            'ngay_ket_thuc' => $request->ngay_ket_thuc,
            'trang_thai' => 1,
        ]);

        return redirect()->route('admin.khuyenmai.index', $request->query())
            ->with('success', 'Thêm mã khuyến mãi thành công!');
    }

    public function edit($id)
    {
        $khuyenMai = KhuyenMai::findOrFail($id);
        return view('admin.khuyenmai.capNhat_KM', compact('khuyenMai'));
    }

    public function update(Request $request, $id)
    {
        $khuyenMai = KhuyenMai::findOrFail($id);

        $request->validate([
            'ten_chuong_trinh' => 'required|string|max:255',
            'gia_tri_giam' => 'required|numeric|gt:0',
            'giam_toi_da' => 'nullable|numeric|gt:0',
            'don_hang_toi_thieu' => 'nullable|numeric|min:0',
            'so_luong' => 'required|integer|gte:' . $khuyenMai->da_dung,
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after:ngay_bat_dau',
            'trang_thai' => 'required|in:0,1',
        ], [
            'so_luong.gte' => "Số lượng không thể nhỏ hơn số lượt đã sử dụng ({$khuyenMai->da_dung}).",
        ]);

        if ($request->loai_giam === 'phan_tram' && $request->gia_tri_giam > 100) {
            return back()->withInput()->withErrors(['gia_tri_giam' => 'Giảm phần trăm không được vượt quá 100%.']);
        }

        $khuyenMai->update([
            'ten_chuong_trinh' => trim($request->ten_chuong_trinh),
            'gia_tri_giam' => $request->gia_tri_giam,
            'giam_toi_da' => $request->giam_toi_da,
            'don_hang_toi_thieu' => $request->don_hang_toi_thieu ?? 0,
            'so_luong' => $request->so_luong,
            'ngay_bat_dau' => $request->ngay_bat_dau,
            'ngay_ket_thuc' => $request->ngay_ket_thuc,
            'trang_thai' => $request->trang_thai,
        ]);

        return redirect()->route('admin.khuyenmai.index', $request->query())
            ->with('success', 'Cập nhật mã khuyến mãi thành công!');
    }

    public function destroy(Request $request, $id)    
    {
        $maKhuyenMai = null;
        $deleted = DB::transaction(function () use ($id, &$maKhuyenMai) {
            $khuyenMai = KhuyenMai::whereKey($id)->lockForUpdate()->firstOrFail();
            $maKhuyenMai = $khuyenMai->ma_khuyen_mai;

            if (
                $khuyenMai->da_dung > 0 ||
                DonHang::where('ma_khuyen_mai', $maKhuyenMai)->exists()
            ) {
                return false;
            }

            $khuyenMai->delete();
            return true;
        });

        if (!$deleted) {
            return redirect()->route('admin.khuyenmai.index', $request->query())
            ->with('error', "Không thể xóa mã {$maKhuyenMai} vì đã được sử dụng trong đơn hàng.");
        }

        return redirect()->route('admin.khuyenmai.index', $request->query())
            ->with('success', "Đã xóa mã {$maKhuyenMai} thành công!");
    }
}