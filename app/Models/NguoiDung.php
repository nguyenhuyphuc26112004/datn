<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\GioHang; 
use App\Models\VaiTro;
use App\Models\DonHang;

class NguoiDung extends Authenticatable
{
    use Notifiable;

    protected $table = 'nguoi_dung';
    public $timestamps = false;

    protected $fillable = [
        'id_vai_tro', 'ho_ten', 'gioi_tinh', 'email', 'ten_dang_nhap',
        'mat_khau', 'so_lan_sai', 'thoi_gian_khoa', 'ma_otp', 'het_han_otp', 'trang_thai'
    ];

    protected $hidden = [
        'mat_khau',
        'remember_token',
    ];

    /**
     * BẮT BUỘC: Khai báo cột mật khẩu thực tế trong DB cho Laravel Auth
     */
    public function getAuthPassword()
    {
        return $this->mat_khau;
    }

    // Khóa ngoại: Người dùng -> Vai trò
    public function vaiTro()
    {
        return $this->belongsTo(VaiTro::class, 'id_vai_tro', 'id');
    }

    // Quan hệ: 1 Người dùng -> 1 Giỏ hàng
    public function gioHang()
    {
        return $this->hasOne(GioHang::class, 'id_nguoi_dung', 'id');
    }

    // Quan hệ: 1 Người dùng -> Nhiều Đơn hàng
    public function donHangs()
    {
        return $this->hasMany(DonHang::class, 'id_nguoi_dung', 'id');
    }
    public function isAdmin()
    {
        return $this->id_vai_tro == 2;
    }

    /**
     * Kiểm tra người dùng có phải NHÂN VIÊN (id = 3)
     */
    public function isStaff()
    {
        return $this->id_vai_tro == 3;
    }
    public function isStaffOrAdmin()
    {
        return in_array($this->id_vai_tro, [2, 3]);
    }

    /**
     * Kiểm tra tài khoản có đang bị khóa không (trang_thai = 0)
     */
    public function isLocked()
    {
        return isset($this->trang_thai) && (int)$this->trang_thai === 0;
    }
}