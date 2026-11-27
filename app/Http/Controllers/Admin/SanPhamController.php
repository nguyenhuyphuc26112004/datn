<?php

namespace App\Http\Controllers\Admin;

use App\Models\SanPham;
use App\Models\LoHang;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class SanPhamController extends Controller
{
    public function index(Request $request)
    {
        $categories = ["Trái cây nhập khẩu", "Trái cây Việt Nam", "Quả sấy khô", "Giỏ trái cây"];
        $query = SanPham::withCount(['ctDonHangs as orders_count']);
        if ($request->filled('search')) {
            $query->where('ten', 'like', '%' . $request->search . '%');
        }
        if (in_array($request->input('loai'), $categories, true)) {
            $query->where('loai', $request->input('loai'));
        }
        $today = Carbon::today();
        if ($request->filled('status')) {
            $status = $request->status;

            if ($status == 'con_han') {
                $query->whereNotNull('ngay_het_han')
                      ->whereDate('ngay_het_han', '>', $today->copy()->addDays(2));
            } elseif ($status == 'sap_het_han') {
                $query->whereNotNull('ngay_het_han')
                      ->whereDate('ngay_het_han', '>=', $today)
                      ->whereDate('ngay_het_han', '<=', $today->copy()->addDays(2));
            } elseif ($status == 'qua_han') {
                $query->whereNotNull('ngay_het_han')
                      ->whereDate('ngay_het_han', '<', $today);
            } elseif ($status == 'het_hang') {
                $query->where('so_luong', '<=', 0);
            }
        }

        $products = $query->orderBy('id', 'asc')->paginate(10)->withQueryString();
        return view('admin.sanpham.quanLySP', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = ["Trái cây nhập khẩu", "Trái cây Việt Nam", "Quả sấy khô", "Giỏ trái cây"];
        $units = ['Kg', 'Giỏ', 'Quả'];
        return view('admin.sanpham.them_SP', compact('categories', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_san_pham' => 'required|unique:san_pham,ten',
            'so_luong' => 'required|numeric|gt:0',
            'gia_nhap' => 'required|numeric|min:0',
            'gia' => 'required|numeric|gt:0',
            'mo_ta' => 'required|string|max:1000',
            'ngay_het_han' => 'required|date|after_or_equal:today',
            'anh_san_pham' => 'required|image|mimes:jpeg,jpg,png'
        ], [
            'ten_san_pham.required'=>'Tên sản phẩm không được để trống.',
            'ten_san_pham.unique'=>'Tên sản phẩm đã tồn tại.',
            'so_luong.required'=>'Số lượng không được để trống.',
            'so_luong.numeric'=>'Số lượng phải là số.',
            'so_luong.gt'=>'Số lượng phải lớn hơn 0.',
            'gia_nhap.required'=>'Giá nhập không được để trống.',
            'gia_nhap.numeric'=>'Giá nhập phải là số.',
            'gia_nhap.min'=>'Giá nhập không được nhỏ hơn 0.',
            'gia.required'=>'Giá không được để trống.',
            'gia.numeric'=>'Giá phải là số.',
            'gia.gt'=>'Giá phải lớn hơn 0.',
            'mo_ta.required'=>'Mô tả sản phẩm không được để trống.',
            'ngay_het_han.required'=>'Hạn sử dụng không được để trống.',
            'ngay_het_han.date'=>'Hạn sử dụng không hợp lệ.',
            'ngay_het_han.after_or_equal'=>'Hạn sử dụng không được nhỏ hơn ngày hôm nay.',
            'anh_san_pham.required'=>'Ảnh sản phẩm không được để trống.',
            'anh_san_pham.image'=>'File tải lên phải là ảnh.',
            'anh_san_pham.mimes'=>'File phải là ảnh (.jpeg, .jpg, .png).',
        ]);

        $tenAnh = null;
        if ($request->hasFile('anh_san_pham')) {
            $file = $request->file('anh_san_pham');
            $tenAnh = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $tenAnh);
        }

        DB::transaction(function () use ($request, $tenAnh) {
            $product = SanPham::create([
                'ten' => trim($request->ten_san_pham),
                'loai' => $request->loai,
                'don_vi' => $request->don_vi,
                'so_luong' => $request->so_luong,
                'gia' => $request->gia,
                'mo_ta' => $request->mo_ta,
                'ngay_het_han' => $request->ngay_het_han,
                'anh' => $tenAnh,
                'trang_thai' => 1
            ]);

            $maLo = 'LH-' . str_pad($product->id, 4, '0', STR_PAD_LEFT) . '-01';
            $product->loHangs()->create([
                'ma_lo' => $maLo,
                'so_luong_nhap' => (int)$request->so_luong,
                'so_luong_con_lai' => (int)$request->so_luong,
                'gia_nhap' => (float)$request->gia_nhap,
                'ngay_nhap' => Carbon::now()->toDateString(), 
                'ngay_het_han' => $request->ngay_het_han,
            ]);
            
            $product->dongBoThongTinLoHang();
        });

        return redirect()->route('admin.sanpham.index', $request->query())
            ->with('success', 'Thêm sản phẩm và tạo lô hàng ban đầu thành công!');
    }

    public function show($id)
    {
        $product = SanPham::with('loHangs')->findOrFail($id);
        $loHangs = LoHang::where('id_san_pham', $id)
            ->orderBy('ngay_het_han', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(3, ['*'], 'lo_hang_page')
            ->withQueryString();
        return view('admin.sanpham.xemCT_SP', compact('product', 'loHangs'));
    }

    public function edit($id)
    {
        $product = SanPham::findOrFail($id);
        $categories = ["Trái cây nhập khẩu", "Trái cây Việt Nam", "Quả sấy khô", "Giỏ trái cây"];
        $units = ['Kg', 'Giỏ', 'Quả'];
        return view('admin.sanpham.capNhat_SP', compact('product', 'categories', 'units'));
    }

    public function update(Request $request, $id)
    {
        $product = SanPham::findOrFail($id);

        $request->validate([
            'ten_san_pham'  => 'required|unique:san_pham,ten,' . $id,
            'mo_ta' => 'required|string|max:1000',
            'gia' => 'required|numeric|gt:0',
            'trang_thai' => 'required|in:0,1',
            'anh_san_pham' => 'nullable|image|mimes:jpeg,jpg,png',
        ], [
            'ten_san_pham.required' => 'Tên sản phẩm không được để trống.',
            'ten_san_pham.unique' => 'Tên sản phẩm đã tồn tại.',
            'mo_ta.required' => 'Mô tả sản phẩm không được để trống.',
            'gia.required' => 'Giá không được để trống.',
            'gia.numeric' => 'Giá phải là số.',
            'gia.gt' => 'Giá phải lớn hơn 0.',
            'trang_thai.required' => 'Vui lòng chọn trạng thái sản phẩm.',
            'trang_thai.in' => 'Trạng thái không hợp lệ.',
            'anh_san_pham.image' => 'File tải lên phải là ảnh.',
            'anh_san_pham.mimes' => 'File phải là ảnh (.jpeg, .jpg, .png).',
        ]);

        $tenAnh = $product->anh;
        if ($request->hasFile('anh_san_pham')) {
            if ($tenAnh && File::exists(public_path('uploads/' . $tenAnh))) {
                File::delete(public_path('uploads/' . $tenAnh));
            }
            $file = $request->file('anh_san_pham');
            $tenAnh = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $tenAnh);
        }

        $product->update([
            'ten' => trim($request->ten_san_pham),
            'loai' => $request->loai,
            'don_vi' => $request->don_vi,
            'mo_ta' => $request->mo_ta,
            'gia' => $request->gia,
            'anh' => $tenAnh,
            'trang_thai'  => $request->trang_thai,
        ]);

        return redirect()->route('admin.sanpham.index', $request->query())
            ->with('success', 'Sửa sản phẩm thành công!');
    }

    public function nhapKho(Request $request, $id)
    {
        $request->validate([
            'ma_lo' => 'nullable|string|max:50',
            'so_luong_nhap' => 'required|numeric|gt:0',
            'gia_nhap_moi' => 'required|numeric|min:0',
            'ngay_het_han_lo_moi' => 'required|date|after_or_equal:today',
        ], [
            'so_luong_nhap.required' => 'Vui lòng nhập số lượng.',
            'so_luong_nhap.gt' => 'Số lượng nhập phải lớn hơn 0.',
            'gia_nhap_moi.required' => 'Vui lòng nhập giá nhập mới.',
            'gia_nhap_moi.min' => 'Giá nhập không được âm.',
            'ngay_het_han_lo_moi.required' => 'Vui lòng nhập hạn sử dụng của lô mới.',
            'ngay_het_han_lo_moi.date' => 'Hạn sử dụng không hợp lệ.',
            'ngay_het_han_lo_moi.after_or_equal' => 'Hạn sử dụng lô mới phải từ hôm nay trở đi.',
        ]);

        DB::transaction(function () use ($request, $id) {
            $product = SanPham::whereKey($id)->lockForUpdate()->firstOrFail();
            $prefix = 'LH-' . str_pad($product->id, 4, '0', STR_PAD_LEFT) . '-';
            
            $maxSoThuTu = $product->loHangs()
                ->where('ma_lo', 'LIKE', $prefix . '%')
                ->pluck('ma_lo')
                ->map(function ($maLo) use ($prefix) {
                    $phanSau = substr($maLo, strlen($prefix));
                    return ctype_digit($phanSau) ? (int) $phanSau : 0;
                })
                ->max() ?? 0;

            $maLo = $prefix . str_pad($maxSoThuTu + 1, 2, '0', STR_PAD_LEFT);
            $product->loHangs()->create([
                'ma_lo' => $maLo,
                'so_luong_nhap' => (int)$request->so_luong_nhap,
                'so_luong_con_lai' => (int)$request->so_luong_nhap,
                'gia_nhap' => (float)$request->gia_nhap_moi,
                'ngay_nhap' => Carbon::now()->toDateString(), 
                'ngay_het_han'=> $request->ngay_het_han_lo_moi,
            ]);
            
            $product->dongBoThongTinLoHang();
        });

        return redirect()->route('admin.sanpham.index', $request->query())
            ->with('success', 'Nhập thêm lô hàng mới thành công!');
    }

    public function destroyLoHang(Request $request, $id, $loHangId)
    {
        $deleted = DB::transaction(function () use ($id, $loHangId) {
            $product = SanPham::whereKey($id)->lockForUpdate()->firstOrFail();
            $loHang = $product->loHangs()->whereKey($loHangId)->lockForUpdate()->firstOrFail();

            if ($product->ctDonHangs()->where('ma_lo', $loHang->ma_lo)->exists()) {
                return false;
            }

            $loHang->delete();
            $product->dongBoThongTinLoHang();

            return true;
        });

        if (!$deleted) {
            return redirect()->route('admin.sanpham.show', ['id' => $id] + $request->query())
                ->with('error', 'Không thể xóa lô đã được dùng trong đơn hàng.');
        }

        return redirect()->route('admin.sanpham.show', ['id' => $id] + $request->query())
            ->with('success', 'Đã xóa lô hàng và cập nhật tồn kho sản phẩm.');
    }

    public function destroy(Request $request, $id)
    {
        $image = null;
        $deleted = DB::transaction(function () use ($id, &$image) {
            $product = SanPham::where('id', $id)->lockForUpdate()->firstOrFail();

            if ($product->ctDonHangs()->exists()) {
                return false;
            }

            $image = $product->anh;
            $product->ctGioHangs()->delete();
            $product->loHangs()->delete();
            $product->delete();

            return true;
        });

        if (!$deleted) {
            return redirect()->route('admin.sanpham.index', $request->query())
                ->with('error', 'Sản phẩm vừa nằm trong đơn hàng nên không thể xóa!');
        }

        if ($image && File::exists(public_path('uploads/' . $image))) {
            File::delete(public_path('uploads/' . $image));
        }

        return redirect()->route('admin.sanpham.index', $request->query())
            ->with('success', 'Xóa vĩnh viễn sản phẩm và các lô hàng thành công!');
    }
}