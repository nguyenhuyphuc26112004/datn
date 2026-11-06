<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GioHang extends Model
{
    protected $table = 'gio_hang';
    public $timestamps = false;

    protected $fillable = ['id_nguoi_dung', 'so_luong_sp'];

    // Khóa ngoại: Giỏ hàng -> Người dùng
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung', 'id');
    }

    public function ctGioHangs()
    {
        return $this->hasMany(CtGioHang::class, 'id_gio_hang', 'id');
    }
}