<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ShippingService
{
    protected $shopAddress;
    protected $apiKey;

    public function __construct()
    {
        $this->shopAddress = config('app.shop_address', env('SHOP_ADDRESS'));
        $this->apiKey = env('GOONG_API_KEY');
    }

    /**
     * Tính khoảng cách giữa 2 địa chỉ bằng Goong Distance Matrix API
     */
    public function getDistanceInKm($customerAddress)
    {
        // Thêm hậu tố Hà Nội để API định vị chính xác
        $fullDestination = $customerAddress . ', Hà Nội';

        $response = Http::get('https://rsapi.goong.io/DistanceMatrix', [
            'origins' => $this->shopAddress,
            'destinations' => $fullDestination,
            'vehicle' => 'bike', // Phương tiện: xe máy
            'api_key' => $this->apiKey,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            
            // Lấy khoảng cách dạng mét (VD: 5400m -> 5.4km)
            if (isset($data['rows'][0]['elements'][0]['distance']['value'])) {
                $distanceMeters = $data['rows'][0]['elements'][0]['distance']['value'];
                return round($distanceMeters / 1000, 1); // Đổi ra Km (làm tròn 1 chữ số)
            }
        }

        return null; // Không tính được khoảng cách
    }

    /**
     * Tính phí giao hàng dựa trên số Km
     */
    public function calculateShippingFee($distanceKm)
    {
        if ($distanceKm === null) {
            return [
                'success' => false,
                'message' => 'Không thể định vị địa chỉ giao hàng.',
                'fee' => 0
            ];
        }

        // Trường hợp > 10Km
        if ($distanceKm > 10) {
            return [
                'success' => false,
                'is_over_limit' => true,
                'message' => "Khoảng cách giao hàng ({$distanceKm}km) vượt quá 10km. Vui lòng liên hệ hotline để hỗ trợ.",
                'fee' => 0
            ];
        }

        // Logic tính tiền dưới 10km
        $baseFee = 15000; // Phí chuẩn cho 3km đầu

        if ($distanceKm <= 3) {
            $fee = $baseFee;
        } else {
            // Từ km thứ 4 đến km thứ 10: mỗi km cộng thêm 5.000đ
            $extraKm = ceil($distanceKm - 3);
            $fee = $baseFee + ($extraKm * 5000);
        }

        return [
            'success' => true,
            'is_over_limit' => false,
            'distance' => $distanceKm,
            'fee' => $fee,
            'message' => "Khoảng cách: {$distanceKm} km"
        ];
    }
}