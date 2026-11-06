<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CtDonHang extends Model
{
    protected $table = 'ct_don_hang';
    public $timestamps = false;

    protected $fillable = ['id_don_hang', 'id_san_pham', 'so_luong', 'ma_lo', 'gia'];

    // Khóa ngoại: Chi tiết đơn hàng -> Đơn hàng
    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'id_don_hang', 'id');
    }

    // Khóa ngoại: Chi tiết đơn hàng -> Sản phẩm
    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'id_san_pham', 'id');
    }
}