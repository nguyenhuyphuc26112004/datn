<?php

namespace App\Http\Controllers\Admin;

use App\Models\SanPham;
use App\Models\LoHang;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SanPhamController extends Controller
{
    public function index(Request $request)
    {
        $query = SanPham::with('loHangs');
        if ($request->filled('search')) {
            $query->where('ten', 'like', '%' . $request->search . '%');
        }
        $today = Carbon::today();
        if ($request->filled('filter')) {
            $filter = $request->filter;

            if ($filter == 'con_han') {
                $query->whereNotNull('ngay_het_han')
                      ->whereDate('ngay_het_han', '>', $today->copy()->addDays(2));
            } elseif ($filter == 'sap_het_han') {
                $query->whereNotNull('ngay_het_han')
                      ->whereDate('ngay_het_han', '>=', $today)
                      ->whereDate('ngay_het_han', '<=', $today->copy()->addDays(2));
            } elseif ($filter == 'qua_han') {
                $query->whereNotNull('ngay_het_han')
                      ->whereDate('ngay_het_han', '<', $today);
            } elseif ($filter == 'het_hang') {
                $query->where('so_luong', '<=', 0);
            }
        }

        $products = $query->orderBy('id', 'asc')->paginate(10);
        return view('admin.sanpham.quanLySP', compact('products'));
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
            'tenSanPham' => 'required|unique:san_pham,ten',
            'soLuong' => 'required|numeric|gt:0',
            'gia_nhap' => 'required|numeric|min:0',
            'gia' => 'required|numeric|gt:0',
            'mo_ta' => 'required|string|max:1000',
            'ngay_het_han' => 'required|date|after_or_equal:today',
            'anhSanPham' => 'required|image|mimes:jpeg,jpg,png'
        ], [
            'tenSanPham.required'=>'Tên sản phẩm không được để trống.',
            'tenSanPham.unique'=>'Tên sản phẩm đã tồn tại.',
            'soLuong.required'=>'Số lượng không được để trống.',
            'soLuong.numeric'=>'Số lượng phải là số.',
            'soLuong.gt'=>'Số lượng phải lớn hơn 0.',
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
            'anhSanPham.required'=>'Ảnh sản phẩm không được để trống.',
            'anhSanPham.image'=>'File tải lên phải là ảnh.',
            'anhSanPham.mimes'=>'File phải là ảnh (.jpeg, .jpg, .png).',
        ]);

        $tenAnh = null;
        if ($request->hasFile('anhSanPham')) {
            $file = $request->file('anhSanPham');
            $tenAnh = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $tenAnh);
        }

        DB::transaction(function () use ($request, $tenAnh) {
            $sanPham = SanPham::create([
                'ten' => trim($request->tenSanPham),
                'loai' => $request->loai,
                'don_vi' => $request->don_vi,
                'so_luong' => $request->soLuong,
                'gia' => $request->gia,
                'mo_ta' => $request->mo_ta,
                'ngay_het_han' => $request->ngay_het_han,
                'anh' => $tenAnh,
                'trang_thai' => 1
            ]);

            $maLo = 'LH-' . str_pad($sanPham->id, 4, '0', STR_PAD_LEFT) . '-01';
            $sanPham->loHangs()->create([
                'ma_lo' => $maLo,
                'so_luong_nhap' => (int)$request->soLuong,
                'so_luong_con_lai' => (int)$request->soLuong,
                'gia_nhap' => (float)$request->gia_nhap,
                'ngay_het_han' => $request->ngay_het_han,
            ]);

            $sanPham->dongBoThongTinLoHang();
        });

        return redirect()->route('admin.sanpham.index')->with('success', 'Thêm sản phẩm và tạo lô hàng ban đầu thành công!');
    }

    public function show($id)
    {
        $product = SanPham::with('loHangs')->findOrFail($id);
        return view('admin.sanpham.xemCT_SP', compact('product'));
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
            'tenSanPham'  => 'required|unique:san_pham,ten,' . $id,
            'mo_ta'       => 'required|string|max:1000',
            'gia'         => 'required|numeric|gt:0',
            'trang_thai'  => 'required|in:0,1',
            'anhSanPham'  => 'nullable|image|mimes:jpeg,jpg,png',
        ], [
            'tenSanPham.required' => 'Tên sản phẩm không được để trống.',
            'tenSanPham.unique'   => 'Tên sản phẩm đã tồn tại.',
            'mo_ta.required'      => 'Mô tả sản phẩm không được để trống.',
            'gia.required'        => 'Giá không được để trống.',
            'gia.numeric'         => 'Giá phải là số.',
            'gia.gt'              => 'Giá phải lớn hơn 0.',
            'trang_thai.required' => 'Vui lòng chọn trạng thái sản phẩm.',
            'trang_thai.in'       => 'Trạng thái không hợp lệ.',
            'anhSanPham.image'    => 'File tải lên phải là ảnh.',
            'anhSanPham.mimes'    => 'File phải là ảnh (.jpeg, .jpg, .png).', 
        ]);

        $tenAnh = $product->anh;
        if ($request->hasFile('anhSanPham')) {
            if ($product->anh && File::exists(public_path('uploads/' . $product->anh))) {
                File::delete(public_path('uploads/' . $product->anh));
            }
            $file = $request->file('anhSanPham');
            $tenAnh = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $tenAnh);
        }

        $product->update([
            'ten'         => trim($request->tenSanPham),
            'loai'        => $request->loai,
            'don_vi'      => $request->don_vi,
            'mo_ta'       => $request->mo_ta,
            'gia'         => $request->gia,
            'anh'         => $tenAnh,
            'trang_thai'  => $request->trang_thai,
        ]);

        $product->dongBoThongTinLoHang();

        return redirect()->route('admin.sanpham.index')->with('success', 'Sửa sản phẩm thành công!');
    }

    public function nhapKho(Request $request, $id)
    {
        $request->validate([
            'ma_lo'            => 'nullable|string|max:50',
            'so_luong_nhap'    => 'required|numeric|gt:0',
            'gia_nhap_moi'     => 'required|numeric|min:0',
            'ngay_het_han_moi' => 'required|date|after_or_equal:today',
        ], [
            'so_luong_nhap.required'      => 'Vui lòng nhập số lượng.',
            'so_luong_nhap.gt'            => 'Số lượng nhập phải lớn hơn 0.',
            'gia_nhap_moi.required'       => 'Vui lòng nhập giá nhập mới.',
            'gia_nhap_moi.min'            => 'Giá nhập không được âm.',
            'ngay_het_han_moi.required'   => 'Vui lòng nhập hạn sử dụng của lô mới.',
            'ngay_het_han_moi.date'       => 'Hạn sử dụng không hợp lệ.',
            'ngay_het_han_moi.after_or_equal' => 'Hạn sử dụng lô mới phải từ hôm nay trở đi.',
        ]);

        $product = SanPham::findOrFail($id);

        DB::transaction(function () use ($request, $product) {
            $countLo = $product->loHangs()->count() + 1;
            $maLo = 'LH-' . str_pad($product->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad($countLo, 2, '0', STR_PAD_LEFT);
            $product->loHangs()->create([
                'ma_lo'            => $maLo,
                'so_luong_nhap'    => (int)$request->so_luong_nhap,
                'so_luong_con_lai' => (int)$request->so_luong_nhap,
                'gia_nhap'         => (float)$request->gia_nhap_moi,
                'ngay_het_han'     => $request->ngay_het_han_moi,
            ]);
            
            $product->dongBoThongTinLoHang();
        });

        return redirect()->route('admin.sanpham.index')->with('success', 'Nhập thêm lô hàng mới thành công! Hệ thống đã ghi nhận lô hàng riêng biệt và tự động cập nhật tổng tồn kho theo FEFO.');
    }

    public function danhSachLoHang($id)
    {
        $product = SanPham::with('loHangs')->findOrFail($id);

        $danhSach = $product->loHangs->map(function ($lo) {
            return [
                'id'               => $lo->id,
                'ma_lo'            => $lo->ma_lo,
                'so_luong_nhap'    => $lo->so_luong_nhap,
                'so_luong_con_lai' => $lo->so_luong_con_lai,
                'gia_nhap'         => number_format($lo->gia_nhap, 0, ',', '.') . ' đ',
                'ngay_het_han'     => $lo->ngay_het_han ? Carbon::parse($lo->ngay_het_han)->format('d/m/Y') : '-',
                'badge'            => $lo->trang_thai_badge,
            ];
        });

        return response()->json([
            'success'  => true,
            'product'  => [
                'id'       => $product->id,
                'ten'      => $product->ten,
                'don_vi'   => $product->don_vi,
                'so_luong' => $product->so_luong,
            ],
            'lo_hang'  => $danhSach
        ]);
    }

    public function delete($id)
    {
        $product = SanPham::findOrFail($id);
        $totalOrders = DB::table('ct_don_hang')->where('id_san_pham', $id)->count();

        if ($totalOrders > 0) {
            return redirect()->route('admin.sanpham.index')->with('error', 'Không thể xóa sản phẩm đã từng nằm trong đơn hàng!');
        }

        return view('admin.sanpham.xoa_SP', compact('product'));
    }

    public function destroy($id)
    {
        $product = SanPham::findOrFail($id);
        $totalOrders = DB::table('ct_don_hang')->where('id_san_pham', $id)->count();

        if ($totalOrders > 0) {
            return redirect()->route('admin.sanpham.index')->with('error', 'Không thể xóa sản phẩm đã từng nằm trong đơn hàng!');
        }

        DB::transaction(function () use ($product) {
            DB::table('ct_gio_hang')->where('id_san_pham', $product->id)->delete();
            $product->loHangs()->delete();

            if ($product->anh && File::exists(public_path('uploads/' . $product->anh))) {
                File::delete(public_path('uploads/' . $product->anh));
            }

            $product->delete();
        });

        return redirect()->route('admin.sanpham.index')->with('success', 'Xóa vĩnh viễn sản phẩm và các lô hàng thành công!');
    }
}