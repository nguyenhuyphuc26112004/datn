<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Models\DonHang;
use App\Models\CtDonHang;
use App\Models\SanPham;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\NguoiDung;


class DashboardController extends Controller
{
    public function index()
    {
       
        $totalUsers = DB::table('nguoi_dung')->count();

        $totalOrders = DB::table('don_hang')->count();

        $totalProducts = DB::table('san_pham')->count();

        $totalPending = DB::table('don_hang')
            ->where('trang_thai', 'Đang chờ duyệt')
            ->count();

        
        $recentOrders = DB::table('don_hang')
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalOrders',
            'totalProducts',
            'totalPending',
            'recentOrders'
        ));
    }

    public function thongKe(Request $request)
    {
        if(!Auth::check() || !Auth::user()->isAdmin()){
            return redirect()->route('admin.dashboard')->with('error','TK của bạn ko có quyền truy cập vào trang thống kê');
        }
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        
        $parsedStart = Carbon::parse($startDate);
        $parsedEnd = Carbon::parse($endDate);

        if ($parsedStart->gt($parsedEnd)) {
            return redirect()->route('admin.thongKe')->with('error', 'Ngày bắt đầu không được lớn hơn ngày kết thúc!');
        }
        
        $totalAllTime = DonHang::where('trang_thai', 'Đã nhận hàng')->sum('tong_tien');
        $filterQuery = DonHang::where('trang_thai', 'Đã nhận hàng')
                    ->whereBetween('ngay_dat', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        $totalFiltered = (clone $filterQuery)->sum('tong_tien');
        $totalRows = (clone $filterQuery)->count();
        $orders = $filterQuery->orderByDesc('ngay_dat')->paginate(15)->withQueryString();

        $currentYear = Carbon::now()->year;
        $monthlyRevenues = DonHang::where('trang_thai', 'Đã nhận hàng')
            ->whereYear('ngay_dat', $currentYear)
            ->selectRaw('MONTH(ngay_dat) as month, SUM(tong_tien) as total')
            ->groupBy(DB::raw('MONTH(ngay_dat)'))
            ->pluck('total', 'month')
            ->toArray();
        $months = [];
        $revenues = [];
        for($m = 1; $m <= 12; $m++){
            $months[] = "T" . $m;
            $revenues[] = $monthlyRevenues[$m] ?? 0;
        }


        $topProducts = CtDonHang::join('san_pham as sp', 'ct_don_hang.id_san_pham', '=', 'sp.id')
            ->join('don_hang as dh', 'ct_don_hang.id_don_hang', '=', 'dh.id')
            ->where('dh.trang_thai', 'Đã nhận hàng')
            ->whereBetween('dh.ngay_dat', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->selectRaw('sp.ten, SUM(ct_don_hang.so_luong) as qty')
            ->groupBy('sp.ten', 'sp.id')
            ->orderByDesc('qty')->take(5)->get();

        $spNames = $topProducts->pluck('ten')->toArray();
        $spQtys  = $topProducts->pluck('qty')->toArray();

        return view('admin.thongKe', compact(
            'startDate',
            'endDate',
            'totalAllTime',
            'totalFiltered',
            'totalRows',
            'orders',
            'months',
            'revenues',
            'spNames',
            'spQtys'
        ));
    }
}
