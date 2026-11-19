<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DonHang;       
use App\Models\CtDonHang; 
use App\Models\SanPham;      
use App\Models\LoHang;       
use App\Models\KhuyenMai;   
use Carbon\Carbon;
use App\Models\LichSuThanhToan;
use Illuminate\Support\Facades\DB;

class AutoCancelUnpaidOrders extends Command
{
    protected $signature = 'orders:cancel-unpaid';

    protected $description = 'Tự động hủy các đơn hàng chuyển khoản VietQR quá hạn và hoàn lại tồn kho chi tiết theo lô';

    public function handle()
    {
        $now = Carbon::now();

        $donQuaHan = DonHang::whereHas('lichSuThanhToan', function ($query) use ($now) {
            $query->where('pt_thanh_toan', 'ONLINE')
                ->where('trang_thai_thanh_toan', 'Chờ xác nhận tiền')
                ->where('thoi_gian_het_han', '<', $now);
        })
            ->with('lichSuThanhToan')
            ->get();

        if ($donQuaHan->isEmpty()) {
            $this->info('Không có đơn hàng nào quá hạn cần hủy.');
            return Command::SUCCESS;
        }

        $count = 0;
        foreach ($donQuaHan as $order) {
            DB::transaction(function () use ($order) {
                $items = CtDonHang::where('id_don_hang', $order->id)->get();

                foreach ($items as $item) {
                    LoHang::where('ma_lo', $item->ma_lo)
                        ->increment('so_luong_con_lai', $item->so_luong);
                    SanPham::find($item->id_san_pham)
                        ->increment('so_luong', $item->so_luong);
                }

                if (!empty($order->ma_khuyen_mai)) {
                    $khuyenMai = KhuyenMai::where('ma_khuyen_mai', $order->ma_khuyen_mai)->first();
                    if ($khuyenMai && $khuyenMai->da_dung > 0) {
                        $khuyenMai->decrement('da_dung');
                    }
                }

                $order->update([
                    'trang_thai' => 'Đã hủy',
                ]);
                LichSuThanhToan::where('id_don_hang', $order->id)
                    ->update([
                        'trang_thai_thanh_toan' => 'Quá hạn thanh toán',
                    ]);
            });

            $count++;
            $this->info("Đã hủy đơn hàng quá hạn #{$order->id} và hoàn tồn kho the lô thành công.");
        }

        $this->info("Tổng cộng đã tự động hủy {$count} đơn hàng quá hạn.");
        return Command::SUCCESS;
    }
}