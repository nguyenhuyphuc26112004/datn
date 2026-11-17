<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DonHang; 

class WebhookController extends Controller
{
    public function handleBankWebhook(Request $request)
    {
        $content = $request->input('content', ''); 
        $amount = $request->input('transferAmount', 0); 

        $order = DonHang::where('trang_thai_thanh_toan', 'Chờ xác nhận tiền')
            ->get()
            ->first(function ($item) use ($content, $amount) {
                return !empty($item->ma_chuyen_khoan) 
                    && str_contains($content, $item->ma_chuyen_khoan)
                    && $amount >= $item->tong_tien;            
                });

        if ($order) {
            $order->trang_thai_thanh_toan = 'Đã thanh toán'; 
            $order->trang_thai = 'Đã xác nhận'; 
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Xác nhận thanh toán thành công cho mã chuyển khoản: ' . $order->ma_chuyen_khoan
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy đơn hàng phù hợp hoặc đã được xử lý trước đó.'
        ], 404);
    }
}