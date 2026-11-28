<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DonHang;
use App\Models\LichSuThanhToan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    public function handleBankWebhook(Request $request)
    {
        $content = $request->input('content', '');
        $amount = $request->input('transferAmount', 0);

        $lichSu = LichSuThanhToan::with('donHang')
            ->where('pt_thanh_toan', 'ONLINE')
            ->where('trang_thai_thanh_toan', 'Chờ xác nhận tiền')
            ->get()
            ->first(function ($item) use ($content, $amount) {
                return !empty($item->ma_chuyen_khoan)
                    && $item->donHang
                    && str_contains($content, $item->ma_chuyen_khoan)
                    && $amount >= $item->so_tien;
            });

        if (!$lichSu) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng phù hợp hoặc đã được xử lý trước đó.'
            ], 404);
        }

        try {
            $updated = DB::transaction(function () use ($lichSu, $content, $amount, $request) {
                $order = DonHang::whereKey($lichSu->id_don_hang)
                    ->lockForUpdate()
                    ->first();

                if (!$order) {
                    return false;
                }

                $payment = LichSuThanhToan::whereKey($lichSu->id)
                    ->lockForUpdate()->first();

                if (
                    !$payment
                    || $order->trang_thai === 'Đã hủy'
                    || $payment->pt_thanh_toan !== 'ONLINE'
                    || $payment->trang_thai_thanh_toan !== 'Chờ xác nhận tiền'
                    || empty($payment->ma_chuyen_khoan)
                    || !str_contains($content, $payment->ma_chuyen_khoan)
                    || $amount < $payment->so_tien
                ) {
                    return false;
                }

                $order->update(['trang_thai' => 'Đã xác nhận']);

                $payment->update([
                    'so_tien' => $amount > 0 ? $amount : $payment->so_tien,
                    'trang_thai_thanh_toan' => 'Đã thanh toán',
                    'thoi_gian_thanh_toan' => now(),
                    'du_lieu_phan_hoi' => json_encode($request->all(), JSON_UNESCAPED_UNICODE),
                ]);

                return true;
            });

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đơn hàng phù hợp hoặc đã được xử lý trước đó.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Xác nhận thanh toán thành công cho mã chuyển khoản: ' . $lichSu->ma_chuyen_khoan
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi cập nhật lịch sử thanh toán: ' . $e->getMessage()
            ], 500);
        }
    }
}