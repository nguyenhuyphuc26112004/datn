<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LoHang extends Model
{
    protected $table = 'lo_hang';

    public $timestamps = false;

    protected $fillable = [
        'id_san_pham',
        'ma_lo',
        'so_luong_nhap',
        'so_luong_con_lai',
        'gia_nhap',
        'ngay_het_han',
    ];

    protected $casts = [
        'ngay_het_han' => 'date',
        'gia_nhap'     => 'decimal:2',
    ];

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'id_san_pham', 'id');
    }

    // public function scopeKhaDung($query)
    // {
    //     return $query->where('so_luong_con_lai', '>', 0)
    //                  ->whereDate('ngay_het_han', '>=', Carbon::today())
    //                  ->orderBy('ngay_het_han', 'asc');
    // }

    public function isHetHan(): bool
    {
        if (!$this->ngay_het_han) return false;
        return Carbon::parse($this->ngay_het_han)->lt(Carbon::today());
    }

    public function isSapHetHan(int $days = 2): bool
    {
        if (!$this->ngay_het_han || $this->isHetHan()) return false;
        $hsd = Carbon::parse($this->ngay_het_han);
        $today = Carbon::today();
        return $hsd->diffInDays($today, false) >= -$days && $hsd->gte($today);
    }

    public function getTrangThaiBadgeAttribute(): array
    {
        if ($this->so_luong_con_lai <= 0) {
            return [
                'label' => 'Đã hết hàng',
                'bg'    => '#f1f5f9',
                'color' => '#64748b',
                'border'=> '#e2e8f0',
            ];
        }

        if ($this->isHetHan()) {
            return [
                'label' => 'Đã quá hạn',
                'bg'    => '#fee2e2',
                'color' => '#991b1b',
                'border'=> '#fecaca',
            ];
        }

        if ($this->isSapHetHan()) {
            return [
                'label' => 'Sắp hết hạn',
                'bg'    => '#fffbeb',
                'color' => '#b45309',
                'border'=> '#fde68a',
            ];
        }

        return [
            'label' => 'Còn hạn',
            'bg'    => '#ecfdf5',
            'color' => '#047857',
            'border'=> '#a7f3d0',
        ];
    }
}
