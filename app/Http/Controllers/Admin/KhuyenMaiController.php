<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhuyenMai;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KhuyenMaiController extends Controller
{
    // Danh sách mã khuyến mãi
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

        $dsKhuyenMai = $query->orderBy('id', 'desc')->paginate(15);

        return view('admin.khuyenmai.quanLyKM', compact('dsKhuyenMai'));
    }

    // Form thêm mã mới
    public function create()
    {
        return view('admin.khuyenmai.them_KM');
    }

    // Lưu mã mới
    public function store(Request $request)
    {
        $request->validate([
            'ma_khuyen_mai'      => 'required|string|max:50|unique:khuyen_mai,ma_khuyen_mai|regex:/^[A-Z0-9_]+$/',
            'ten_chuong_trinh'   => 'required|string|max:255',
            'loai_giam'          => 'required|in:phan_tram,tien_mat',
            'gia_tri_giam'       => 'required|numeric|gt:0',
            'giam_toi_da'        => 'nullable|numeric|gt:0',
            'don_hang_toi_thieu' => 'nullable|numeric|min:0',
            'so_luong'           => 'required|integer|gt:0',
            'ngay_bat_dau'       => 'required|date',
            'ngay_ket_thuc'      => 'required|date|after:ngay_bat_dau',
        ], [
            'ma_khuyen_mai.required'    => 'Vui lòng nhập mã khuyến mãi.',
            'ma_khuyen_mai.unique'      => 'Mã khuyến mãi đã tồn tại.',
            'ma_khuyen_mai.regex'       => 'Mã chỉ được chứa chữ IN HOA, số và dấu gạch dưới (_).',
            'ten_chuong_trinh.required' => 'Vui lòng nhập tên chương trình.',
            'gia_tri_giam.required'     => 'Vui lòng nhập giá trị giảm.',
            'gia_tri_giam.gt'           => 'Giá trị giảm phải lớn hơn 0.',
            'so_luong.required'         => 'Vui lòng nhập số lượng mã.',
            'ngay_ket_thuc.after'       => 'Ngày kết thúc phải sau ngày bắt đầu.',
        ]);

        // Validate: giảm % không được > 100
        if ($request->loai_giam === 'phan_tram' && $request->gia_tri_giam > 100) {
            return back()->withInput()->withErrors(['gia_tri_giam' => 'Giảm phần trăm không được vượt quá 100%.']);
        }

        KhuyenMai::create([
            'ma_khuyen_mai'      => strtoupper(trim($request->ma_khuyen_mai)),
            'ten_chuong_trinh'   => trim($request->ten_chuong_trinh),
            'loai_giam'          => $request->loai_giam,
            'gia_tri_giam'       => $request->gia_tri_giam,
            'giam_toi_da'        => $request->giam_toi_da,
            'don_hang_toi_thieu' => $request->don_hang_toi_thieu ?? 0,
            'so_luong'           => $request->so_luong,
            'da_dung'            => 0,
            'ngay_bat_dau'       => $request->ngay_bat_dau,
            'ngay_ket_thuc'      => $request->ngay_ket_thuc,
            'trang_thai'         => 1,
        ]);

        return redirect()->route('admin.khuyenmai.index')->with('success', 'Thêm mã khuyến mãi thành công!');
    }

    // Form sửa mã
    public function edit($id)
    {
        $khuyenMai = KhuyenMai::findOrFail($id);
        return view('admin.khuyenmai.capNhat_KM', compact('khuyenMai'));
    }

    // Cập nhật mã
    public function update(Request $request, $id)
    {
        $khuyenMai = KhuyenMai::findOrFail($id);

        $request->validate([
            'ten_chuong_trinh'   => 'required|string|max:255',
            'gia_tri_giam'       => 'required|numeric|gt:0',
            'giam_toi_da'        => 'nullable|numeric|gt:0',
            'don_hang_toi_thieu' => 'nullable|numeric|min:0',
            'so_luong'           => 'required|integer|gte:' . $khuyenMai->da_dung,
            'ngay_bat_dau'       => 'required|date',
            'ngay_ket_thuc'      => 'required|date|after:ngay_bat_dau',
            'trang_thai'         => 'required|in:0,1',
        ], [
            'so_luong.gte' => "Số lượng không thể nhỏ hơn số lượt đã sử dụng ({$khuyenMai->da_dung}).",
        ]);

        if ($request->loai_giam === 'phan_tram' && $request->gia_tri_giam > 100) {
            return back()->withInput()->withErrors(['gia_tri_giam' => 'Giảm phần trăm không được vượt quá 100%.']);
        }

        $khuyenMai->update([
            'ten_chuong_trinh'   => trim($request->ten_chuong_trinh),
            'gia_tri_giam'       => $request->gia_tri_giam,
            'giam_toi_da'        => $request->giam_toi_da,
            'don_hang_toi_thieu' => $request->don_hang_toi_thieu ?? 0,
            'so_luong'           => $request->so_luong,
            'ngay_bat_dau'       => $request->ngay_bat_dau,
            'ngay_ket_thuc'      => $request->ngay_ket_thuc,
            'trang_thai'         => $request->trang_thai,
        ]);

        return redirect()->route('admin.khuyenmai.index')->with('success', 'Cập nhật mã khuyến mãi thành công!');
    }

    // Xóa mã
    public function destroy($id)    
    {
        $khuyenMai = KhuyenMai::findOrFail($id);

        if ($khuyenMai->da_dung > 0) {
            return back()->with('error', "Không thể xóa mã đã được sử dụng {$khuyenMai->da_dung} lần!");
        }

        $khuyenMai->delete();

        return redirect()->route('admin.khuyenmai.index')->with('success', "Đã xóa mã {$khuyenMai->ma_khuyen_mai} thành công!");
    }
}

