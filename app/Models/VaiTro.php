<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\NguoiDung;

class VaiTro extends Model
{
    protected $table = 'vai_tro';
    public $timestamps = false;

    protected $fillable = ['ten'];

    // Quan hệ: 1 Vai trò -> Nhiều Người dùng
    public function nguoiDungs()
    {
        return $this->hasMany(NguoiDung::class, 'id_vai_tro', 'id');
    }
}