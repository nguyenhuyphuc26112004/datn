<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CtGioHang extends Model
{
    protected $table = 'ct_gio_hang';
    public $timestamps = false;

    protected $fillable = ['id_gio_hang', 'id_san_pham', 'so_luong'];

    // Khóa ngoại: Chi tiết giỏ hàng -> Giỏ hàng
    public function gioHang()
    {
        return $this->belongsTo(GioHang::class, 'id_gio_hang', 'id');
    }

    // Khóa ngoại: Chi tiết giỏ hàng -> Sản phẩm
    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'id_san_pham', 'id');
    }
}