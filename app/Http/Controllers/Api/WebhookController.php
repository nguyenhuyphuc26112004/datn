<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

        if ($lichSu) {
            $order = $lichSu->donHang;
            DB::beginTransaction();
            try {
                $order->trang_thai = 'Đã xác nhận';
                $order->save();

                $duLieuPhanHoi = json_encode($request->all(), JSON_UNESCAPED_UNICODE);

                $lichSu->update([
                    'so_tien'              => $amount > 0 ? $amount : $lichSu->so_tien,
                    'trang_thai_thanh_toan' => 'Đã thanh toán',
                    'thoi_gian_thanh_toan' => now(),
                    'du_lieu_phan_hoi'     => $duLieuPhanHoi,
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Xác nhận thanh toán thành công cho mã chuyển khoản: ' . $lichSu->ma_chuyen_khoan
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi cập nhật lịch sử thanh toán: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy đơn hàng phù hợp hoặc đã được xử lý trước đó.'
        ], 404);
    }
}