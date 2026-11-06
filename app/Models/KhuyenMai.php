<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KhuyenMai extends Model
{
    public $timestamps = false;
    protected $table = 'khuyen_mai';

    protected $fillable = [
        'ma_khuyen_mai', 'ten_chuong_trinh', 'loai_giam',
        'gia_tri_giam', 'giam_toi_da', 'don_hang_toi_thieu',
        'so_luong', 'da_dung', 'ngay_bat_dau', 'ngay_ket_thuc', 'trang_thai'
    ];

    protected $casts = [
        'ngay_bat_dau' => 'datetime',
        'ngay_ket_thuc' => 'datetime',
    ];

    public function kiemTraHopLe($idNguoiDung, float $tongTien): ?string
    {
        if ($this->trang_thai != 1) {
            return 'Mã khuyến mãi này đã bị vô hiệu hóa.';
        }

        $now = Carbon::now();
        if ($now->lt($this->ngay_bat_dau)) {
            return 'Mã khuyến mãi chưa đến thời gian áp dụng.';
        }
        if ($now->gt($this->ngay_ket_thuc)) {
            return 'Mã khuyến mãi đã hết hạn sử dụng.';
        }

        if ($this->da_dung >= $this->so_luong) {
            return 'Mã khuyến mãi đã hết lượt sử dụng.';
        }

        if ($tongTien < $this->don_hang_toi_thieu) {
            $min = number_format($this->don_hang_toi_thieu, 0, ',', '.');
            return "Đơn hàng cần đạt tối thiểu {$min}đ để áp dụng mã này.";
        }

        if ($idNguoiDung) {
            $daSDung = DonHang::where('id_nguoi_dung', $idNguoiDung)
                ->where('ma_khuyen_mai', $this->ma_khuyen_mai)
                ->whereNotIn('trang_thai', ['Đã hủy', 'Đã trả hàng'])
                ->exists();

            if ($daSDung) {
                return 'Bạn đã sử dụng mã "' . $this->ma_khuyen_mai . '" trước đó rồi. Mỗi người chỉ được dùng 1 lần.';
            }
        }

        return null;
    }

    
    public function tinhTienGiam(float $tongTien): float
    {
        if ($this->loai_giam === 'tien_mat') {
            $tienGiam = (float) $this->gia_tri_giam;
        } else {
            // Giảm theo %
            $tienGiam = $tongTien * (float) $this->gia_tri_giam / 100;

            // Áp dụng mức giảm tối đa nếu có
            if ($this->giam_toi_da && $tienGiam > $this->giam_toi_da) {
                $tienGiam = (float) $this->giam_toi_da;
            }
        }
        return min($tienGiam, $tongTien);
    }
    public function conHieuLuc()
    {
        return $this->trang_thai == 1 
            && now()->between($this->ngay_bat_dau, $this->ngay_ket_thuc) 
            && ($this->so_luong > $this->da_dung);
    }
}