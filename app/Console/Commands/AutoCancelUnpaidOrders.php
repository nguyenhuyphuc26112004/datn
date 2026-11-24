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
            $cancelled = DB::transaction(function () use ($order) {
                $order = DonHang::whereKey($order->id)->lockForUpdate()->firstOrFail();
                $lichSu = LichSuThanhToan::where('id_don_hang', $order->id)->lockForUpdate()->first();

                if (
                    $order->trang_thai === 'Đã hủy'
                    || !$lichSu
                    || $lichSu->pt_thanh_toan !== 'ONLINE'
                    || $lichSu->trang_thai_thanh_toan !== 'Chờ xác nhận tiền'
                    || !$lichSu->thoi_gian_het_han
                    || $lichSu->thoi_gian_het_han >= Carbon::now()
                ) {
                    return false;
                }

                $items = CtDonHang::where('id_don_hang', $order->id)->lockForUpdate()->get();

                foreach ($items as $item) {
                    $loHang = LoHang::where('ma_lo', $item->ma_lo)->lockForUpdate()->firstOrFail();
                    $loHang->increment('so_luong_con_lai', $item->so_luong);

                    $sanPham = SanPham::whereKey($item->id_san_pham)->lockForUpdate()->firstOrFail();
                    $sanPham->increment('so_luong', $item->so_luong);
                }

                if (!empty($order->ma_khuyen_mai)) {
                    $khuyenMai = KhuyenMai::where('ma_khuyen_mai', $order->ma_khuyen_mai)
                        ->lockForUpdate()
                        ->first();
                    if ($khuyenMai && $khuyenMai->da_dung > 0) {
                        $khuyenMai->decrement('da_dung');
                    }
                }

                $order->update([
                    'trang_thai' => 'Đã hủy',
                ]);
                $lichSu->update(['trang_thai_thanh_toan' => 'Quá hạn thanh toán']);

                return true;
            });

            if ($cancelled) {
                $count++;
                $this->info("Đã hủy đơn hàng quá hạn #{$order->id} và hoàn tồn kho theo lô thành công.");
            }
        }

        $this->info("Tổng cộng đã tự động hủy {$count} đơn hàng quá hạn.");
        return Command::SUCCESS;
    }
}