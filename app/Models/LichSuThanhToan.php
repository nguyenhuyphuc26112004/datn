<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichSuThanhToan extends Model
{
    protected $table = 'lich_su_thanh_toan';

    public $timestamps = false;

    protected $fillable = [
        'id_don_hang',
        'ma_chuyen_khoan',
        'pt_thanh_toan',
        'so_tien',
        'trang_thai_thanh_toan',
        'thoi_gian_thanh_toan',
        'thoi_gian_het_han',
        'du_lieu_phan_hoi',
    ];

    protected $casts = [
        'so_tien' => 'decimal:2',
        'thoi_gian_thanh_toan' => 'datetime',
        'thoi_gian_het_han' => 'datetime',
    ];

    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'id_don_hang', 'id');
    }
}