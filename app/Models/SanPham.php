<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SanPham extends Model
{
    protected $table = 'san_pham';
    public $timestamps = false;

    protected $fillable = [
        'ten', 
        'loai', 
        'don_vi', 
        'mo_ta', 
        'so_luong', 
        'gia',        
        'anh', 
        'trang_thai', 
        'ngay_het_han'
    ];

    public function ctGioHangs()
    {
        return $this->hasMany(CtGioHang::class, 'id_san_pham', 'id');
    }

    public function ctDonHangs()
    {
        return $this->hasMany(CtDonHang::class, 'id_san_pham', 'id');
    }

    public function loHangs()
    {
        return $this->hasMany(LoHang::class, 'id_san_pham', 'id')->orderBy('ngay_het_han', 'asc');
    }

    public function loHangsKhaDung()
    {
        return $this->hasMany(LoHang::class, 'id_san_pham', 'id')
                    ->where('so_luong_con_lai', '>', 0)
                    ->whereDate('ngay_het_han', '>=', Carbon::today())
                    ->orderBy('ngay_het_han', 'asc');
    }

    
    public function dongBoThongTinLoHang(): void
    {

        $loKhaDung = $this->loHangsKhaDung()->get();
        $tongTonKho = $loKhaDung->sum('so_luong_con_lai');
        $loDauTien  = $loKhaDung->first();
        $hsdGanNhat = $loDauTien ? $loDauTien->ngay_het_han : null;

        $this->update([
            'so_luong'     => $tongTonKho,
            'trang_thai'   => ($tongTonKho > 0) ? 1 : 0
        ]);
    }

    public function xuatKhoTheoFEFO(int $soLuongXuat): array
    {
        $ketQuaXuat = [];

        $loHangs = $this->loHangsKhaDung()->lockForUpdate()->get();
        $tongKhaDung = $loHangs->sum('so_luong_con_lai');

        if ($tongKhaDung < $soLuongXuat) {
            return [];
        }

        $conLaiCanXuat = $soLuongXuat;
        foreach ($loHangs as $lo) {
            if ($conLaiCanXuat == 0) 
                break;

            if ($lo->so_luong_con_lai <= $conLaiCanXuat) {
                $layTuLo = $lo->so_luong_con_lai;
                $conLaiCanXuat = $conLaiCanXuat - $layTuLo;
                $lo->update(['so_luong_con_lai' => 0]);

                $ketQuaXuat[] = [
                    'ma_lo' => $lo->ma_lo,
                    'so_luong' => $layTuLo
                ];
            } else {
                $layTuLo = $conLaiCanXuat;
                $lo->decrement('so_luong_con_lai', $layTuLo);
                $conLaiCanXuat = 0;

                $ketQuaXuat[] = [
                    'ma_lo' => $lo->ma_lo,
                    'so_luong' => $layTuLo
                ];
            }
        }

        $this->dongBoThongTinLoHang();

        return $ketQuaXuat;
    }
}