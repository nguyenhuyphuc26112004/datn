<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\NguoiDung;
use App\Models\CtDonHang;

class DonHang extends Model
{
    protected $table = 'don_hang';
    public $timestamps = false;

    protected $fillable = [
        'id_nguoi_dung', 'ten', 'sdt', 'email', 'dia_chi',
        'tong_tien', 'phi_van_chuyen', 'trang_thai', 'trang_thai_thanh_toan',
        'ma_chuyen_khoan', 'pt_thanh_toan', 'ngay_dat',
        'ma_khuyen_mai', 'tien_giam', 'het_han_thanh_toan',
        'ly_do_tra_hang', 'ly_do_tu_choi_tra', 'hinh_anh_tra', 'het_han_tra_hang', 'latitude', 'longitude'
    ];

    // Khóa ngoại: Đơn hàng -> Người dùng
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung', 'id');
    }

    // Quan hệ: 1 Đơn hàng -> Nhiều Chi tiết đơn hàng
    public function ctDonHangs()
    {
        return $this->hasMany(CtDonHang::class, 'id_don_hang', 'id');
    }
}