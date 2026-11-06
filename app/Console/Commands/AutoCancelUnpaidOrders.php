<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DonHang;       
use App\Models\ChiTietDonHang; 
use App\Models\SanPham;      
use App\Models\LoHang;       
use App\Models\KhuyenMai;   
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AutoCancelUnpaidOrders extends Command
{
    protected $signature = 'orders:cancel-unpaid';

    protected $description = 'Tự động hủy các đơn hàng chuyển khoản VietQR quá hạn và hoàn lại tồn kho chi tiết theo lô bằng Eloquent Model';

    public function handle()
    {
        $now = Carbon::now();

        $expiredOrders = DonHang::where('pt_thanh_toan', 'ONLINE')
            ->where('trang_thai_thanh_toan', 'Chờ xác nhận tiền')
            ->where('trang_thai', 'Đang chờ duyệt')
            ->whereNotNull('het_han_thanh_toan')
            ->where('het_han_thanh_toan', '<', $now)
            ->get();

        if ($expiredOrders->isEmpty()) {
            $this->info('Không có đơn hàng nào quá hạn cần hủy.');
            return Command::SUCCESS;
        }

        $count = 0;
        foreach ($expiredOrders as $order) {
            DB::transaction(function () use ($order) {
                $items = ChiTietDonHang::where('id_don_hang', $order->id)->get();

                foreach ($items as $item) {
                    LoHang::where('ma_lo', $item->ma_lo)
                        ->increment('so_luong_con_lai', $item->so_luong);
                    SanPham::find($item->id_san_pham)
                        ->increment('so_luong', $item->so_luong);
                }

                if (!empty($order->ma_khuyen_mai) && (float)$order->tien_giam > 0) {
                    $khuyenMai = KhuyenMai::where('ma_khuyen_mai', $order->ma_khuyen_mai)->first();
                    if ($khuyenMai && $khuyenMai->da_dung > 0) {
                        $khuyenMai->decrement('da_dung');
                    }
                }

                $order->update([
                    'trang_thai' => 'Đã hủy',
                    'trang_thai_thanh_toan' => 'Quá hạn thanh toán',
                ]);
            });

            $count++;
            $this->info("Đã hủy đơn hàng quá hạn #{$order->id} và hoàn tồn kho theo lô thành công.");
        }

        $this->info("Tổng cộng đã tự động hủy {$count} đơn hàng quá hạn.");
        return Command::SUCCESS;
    }
}