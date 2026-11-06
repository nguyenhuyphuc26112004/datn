<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\DonHang;
use App\Models\KhuyenMai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoucherController extends Controller
{
    public function danhSach()
    {
        $now = now();
        $daDungSet = collect();
        
        if (session()->has('idNguoiDung')) {
            $idNguoiDung = session('idNguoiDung');
            $daDungSet = DonHang::where('id_nguoi_dung', $idNguoiDung)
                ->whereNotIn('trang_thai', ['Đã hủy', 'Đã trả hàng'])
                ->whereNotNull('ma_khuyen_mai')
                ->pluck('ma_khuyen_mai')
                ->map(fn($m) => strtoupper(trim($m)))
                ->toBase();
        }

        $danhSach = KhuyenMai::where('trang_thai', 1)
            ->where('ngay_bat_dau', '<=', $now)
            ->where('ngay_ket_thuc', '>=', $now)
            ->whereColumn('da_dung', '<', 'so_luong')
            ->orderBy('ngay_ket_thuc', 'asc')
            ->get()
            ->map(function ($km) use ($daDungSet) {
                if ($km->loai_giam === 'phan_tram') {
                    $phanTram = rtrim(rtrim(number_format($km->gia_tri_giam, 2), '0'), '.');
                    $giaTri = 'Giảm ' . $phanTram . '%';
                    
                    if (!empty($km->giam_toi_da) && $km->giam_toi_da > 0) {
                        $giaTri .= ' (tối đa ' . number_format($km->giam_toi_da, 0, ',', '.') . 'đ)';
                    } else {
                        $giaTri .= ' (Không giới hạn)';
                    }
                } else {
                    $giaTri = 'Giảm ' . number_format($km->gia_tri_giam, 0, ',', '.') . 'đ';
                }

                $soNgayConLai = (int) ceil(now()->diffInSeconds($km->ngay_ket_thuc, false) / 86400);
                if ($soNgayConLai <= 1) {
                    $conLaiLabel = 'Hết hạn hôm nay!';
                } elseif ($soNgayConLai <= 3) {
                    $conLaiLabel = "Còn {$soNgayConLai} ngày";
                } else {
                    $conLaiLabel = null;
                }

                return [
                    'ma_khuyen_mai' => $km->ma_khuyen_mai,
                    'ten_chuong_trinh' => $km->ten_chuong_trinh,
                    'loai_giam' => $km->loai_giam,
                    'gia_tri_hien_thi' => $giaTri,
                    'don_hang_toi_thieu' => $km->don_hang_toi_thieu,
                    'con_lai' => $km->so_luong - $km->da_dung,
                    'ngay_ket_thuc' => $km->ngay_ket_thuc->format('d/m/Y'),
                    'con_lai_label' => $conLaiLabel,
                    'da_dung_boi_ban' => $daDungSet->contains(strtoupper(trim($km->ma_khuyen_mai))),
                ];
            });

        return response()->json(['success' => true, 'data' => $danhSach]);
    }

    public function apDung(Request $request)
    {
        $request->validate([
            'ma_khuyen_mai' => 'required|string',
            'tong_tien' => 'required|numeric|min:0',
        ]);

        $ma = strtoupper(trim($request->ma_khuyen_mai));
        $tongTien = (float) $request->tong_tien;
        $idNguoiDung = session('idNguoiDung');

        return DB::transaction(function () use ($ma, $tongTien, $idNguoiDung) {
            $khuyenMai = KhuyenMai::where('ma_khuyen_mai', $ma)->lockForUpdate()->first();
            if (!$khuyenMai) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã khuyến mãi không tồn tại trong hệ thống.'
                ]);
            }
            
            $errorMsg = $khuyenMai->kiemTraHopLe($idNguoiDung, $tongTien);
            if ($errorMsg) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMsg
                ]);
            }
            
            $tienGiam = $khuyenMai->tinhTienGiam($tongTien);
            $tongSauGiam = max(0, $tongTien - $tienGiam);

            return response()->json([
                'success' => true,
                'message' => "Áp dụng thành công! Bạn được giảm " . number_format($tienGiam, 0, ',', '.') . 'đ.',
                'tien_giam' => $tienGiam,
                'tong_sau_giam' => $tongSauGiam,
                'ten_chuong_trinh' => $khuyenMai->ten_chuong_trinh,
            ]);
        });
    }
}