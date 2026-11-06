<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{

    public function index()
    {
        $products = SanPham::where('trang_thai', 1)
            ->whereDate('ngay_het_han', '>=', Carbon::today())
            ->paginate(12);

        return view('client.home', compact('products'));
    }


    // public function detailProduct($id)
    // {
    //     $product = SanPham::where('id', $id)->firstOrFail();

    //     return view('client.chiTietSanPham', compact('product'));
    // }

    public function detailProduct($id)
    {
        $product = SanPham::where('id', $id)
            ->where('trang_thai', 1)
            ->whereDate('ngay_het_han', '>=', Carbon::today()) // Kiểm tra hạn sử dụng >= ngày hôm nay
            ->first();

        if (!$product) {
            return redirect()->route('client.home')->with('error', 'Sản phẩm không tồn tại, đã ngừng kinh doanh hoặc đã hết hạn sử dụng!');
        }

        return view('client.chiTietSanPham', compact('product'));
    }

    
    public function vietnamFruits()
    {
        $products = SanPham::where('loai', 'Trái cây Việt Nam')
            ->where('trang_thai', 1)
            ->whereDate('ngay_het_han', '>=', Carbon::today())
            ->paginate(8);

        return view('client.traiCayVN', compact('products'));
    }

    public function importedFruits()
    {
        $products = SanPham::where('loai', 'Trái cây nhập khẩu')
            ->where('trang_thai', 1)
            ->whereDate('ngay_het_han', '>=', Carbon::today())
            ->paginate(8);

        return view('client.traiCayNK', compact('products'));
    }

    public function dryFruits()
    {
        $products = SanPham::where('loai', 'Quả sấy khô')
            ->where('trang_thai', 1)
            ->whereDate('ngay_het_han', '>=', Carbon::today())
            ->paginate(8);

        return view('client.quaSayKho', compact('products'));
    }

    public function fruitBaskets()
    {
        $products = SanPham::where('loai', 'Giỏ trái cây')
            ->where('trang_thai', 1)
            ->whereDate('ngay_het_han', '>=', Carbon::today())
            ->paginate(8);

        return view('client.gioTraiCay', compact('products'));
    }

    public function fruitDrinks()
    {
        $products = SanPham::where('loai', 'Đồ uống trái cây')
            ->where('trang_thai', 1)
            ->paginate(8);

        return view('client.doUongTraiCay', compact('products'));
    }

    
    public function search(Request $request)
    {
        $query = $request->input('query', '');

        $products = SanPham::where('trang_thai', 1)
            ->whereDate('ngay_het_han', '>=', Carbon::today())
            ->where(function($q) use ($query) {
                $q->where('ten', 'LIKE', "%{$query}%")
                  ->orWhere('loai', 'LIKE', "%{$query}%");
            })
            ->paginate(12);

        return view('client.search', compact('products', 'query'));
    }
}