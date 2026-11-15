<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DonHang; // Dùng Model DonHang của dự án

class WebhookController extends Controller
{
    public function handleBankWebhook(Request $request)
    {
        // 1. Lấy nội dung chuyển khoản và số tiền từ request gửi tới
        $content = $request->input('content', ''); // Nội dung chuyển khoản ngân hàng
        $amount = $request->input('transferAmount', 0); // Số tiền thực tế

        // 2. Tìm đơn hàng có mã chuyển khoản khớp xuất hiện bên trong nội dung tin nhắn chuyển khoản
        // (Ví dụ nội dung khách ghi: "Chuyen khoan thanh toan DH1731562842")
        $order = DonHang::where('trang_thai_thanh_toan', 'Chờ xác nhận tiền')
            ->get()
            ->first(function ($item) use ($content) {
                return !empty($item->ma_chuyen_khoan) && stripos($content, $item->ma_chuyen_khoan) !== false;
            });

        // 3. Nếu tìm thấy đơn hàng phù hợp
        if ($order) {
            // Cập nhật trạng thái thanh toán để tránh bị lệnh AutoCancel hủy đơn
            $order->trang_thai_thanh_toan = 'Đã thanh toán'; 
            $order->trang_thai = 'Đã xác nhận'; // Trạng thái chuẩn bị hàng trong hệ thống của em
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