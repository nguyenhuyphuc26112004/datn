<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\NguoiDung;
use App\Models\DonHang;
use App\Models\VaiTro;

class NguoiDungController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $isAdmin = auth()->user()->isAdmin();
        $accountType = $request->input('account_type');

        $users = NguoiDung::join('vai_tro', 'nguoi_dung.id_vai_tro', '=', 'vai_tro.id')
            ->select(
                'nguoi_dung.*',
                'vai_tro.ten as ten_vai_tro'
            )
            ->addSelect([
                'orders_count' => DonHang::selectRaw('COUNT(*)')
                    ->whereColumn('don_hang.id_nguoi_dung', 'nguoi_dung.id')
            ])
            ->when(auth()->user()->isStaff(), function ($query) {
                return $query->where('vai_tro.ten', 'USER');
            })
            ->when(auth()->user()->isAdmin(), function ($query) {
                return $query->whereIn('vai_tro.ten', ['USER', 'STAFF']);
            })
            ->when($isAdmin && in_array($accountType, ['staff', 'customer'], true), function ($query) use ($accountType) {
                $roleName = $accountType === 'staff' ? 'STAFF' : 'USER';
                return $query->where('vai_tro.ten', $roleName);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nguoi_dung.id', 'LIKE', "%{$search}%")
                      ->orWhere('nguoi_dung.ho_ten', 'LIKE', "%{$search}%")
                      ->orWhere('nguoi_dung.ten_dang_nhap', 'LIKE', "%{$search}%")
                      ->orWhere('nguoi_dung.email', 'LIKE', "%{$search}%");
                });
            })
            ->orderByDesc('nguoi_dung.id')->paginate(15)->withQueryString();

        return view('admin.nguoidung.quanLyND', compact('users', 'isAdmin'));
    }

    public function create(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('admin.user.index', $request->query())->with('error', 'Chỉ ADMIN mới có quyền thêm người dùng!');
        }

        $roles = VaiTro::whereIn('ten', ['USER', 'STAFF'])->get();

        return view('admin.nguoidung.them_ND', compact('roles'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('admin.user.index', $request->query())->with('error', 'Chỉ ADMIN mới có quyền thêm người dùng!');
        }

        $request->validate([
            'ho_ten' => 'required|string|max:255',
            'ten_dang_nhap' => 'required|string|max:255|unique:nguoi_dung,ten_dang_nhap',
            'email' => 'required|email|max:255|unique:nguoi_dung,email',
            'mat_khau' => 'required|string|min:6',
            'gioi_tinh' => 'required|in:Nam,Nữ',
            'id_vai_tro' => 'required|exists:vai_tro,id',
        ], [
            'ho_ten.required' => 'Họ và tên không được để trống.',
            'ten_dang_nhap.required' => 'Tên đăng nhập không được để trống.',
            'ten_dang_nhap.unique' => 'Tên đăng nhập này đã tồn tại trên hệ thống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Địa chỉ Email không hợp lệ.',
            'email.unique' => 'Email này đã tồn tại trên hệ thống.',
            'mat_khau.required' => 'Mật khẩu không được để trống.',
            'mat_khau.min' => 'Mật khẩu phải chứa ít nhất 6 ký tự.',
            'gioi_tinh.required' => 'Vui lòng chọn giới tính.',
            'id_vai_tro.required' => 'Vui lòng chọn vai trò.',
            'id_vai_tro.exists' => 'Bạn chỉ có thể tạo tài khoản với vai trò USER hoặc STAFF.',
        ]);

        NguoiDung::create([
            'ho_ten' => $request->ho_ten,
            'ten_dang_nhap' => $request->ten_dang_nhap,
            'email' => $request->email,
            'mat_khau' => Hash::make($request->mat_khau),
            'gioi_tinh' => $request->gioi_tinh,
            'id_vai_tro' => $request->id_vai_tro,
            'trang_thai' => 1,
        ]);

        return redirect()->route('admin.user.index', $request->query())->with('success', 'Thêm người dùng mới thành công!');
    }

    public function show($id)
    {
        $user = NguoiDung::findOrFail($id);

        return view('admin.nguoidung.xemCT_ND', compact('user'));
    }

    public function edit(Request $request, $id)
    {
        $currentUser = auth()->user();
        $user = NguoiDung::findOrFail($id);

        if ($currentUser && $currentUser->isStaff() && $user->id_vai_tro != 1) { 
            return redirect()->route('admin.user.index', $request->query())
                ->with('error', 'Nhân viên chỉ có quyền chỉnh sửa tài khoản khách hàng!');
        }
        $rolesQuery = VaiTro::query();
        if ($currentUser && $currentUser->isStaff()) {
            $rolesQuery->where('ten', 'USER');
        }
        $roles = $rolesQuery->get();
        return view('admin.nguoidung.capNhat_ND', compact('user', 'roles'));
    }
    public function update(Request $request, $id)
    {
        $currentUser = auth()->user();
        $user = NguoiDung::findOrFail($id);
        
        if ($currentUser && $currentUser->isStaff() && $user->id_vai_tro != 1) { 
            return redirect()->route('admin.user.index', $request->query())->with('error', 'Nhân viên chỉ có quyền chỉnh sửa tài khoản khách hàng!');
        }

        $request->validate([
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:nguoi_dung,email,' . $id,
            'gioi_tinh' => 'required|in:Nam,Nữ',
            'id_vai_tro' => 'required|exists:vai_tro,id',
            'trang_thai' => 'nullable|in:0,1',
        ], [
            'ho_ten.required' => 'Họ và tên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Địa chỉ Email không hợp lệ.',
            'email.unique' => 'Email này đã tồn tại trên hệ thống.',
            'gioi_tinh.required' => 'Vui lòng chọn giới tính.',
            'id_vai_tro.required' => 'Vui lòng chọn vai trò.',
        ]);

        $updateData = [
            'ho_ten' => $request->ho_ten,
            'email' => $request->email,
            'gioi_tinh' => $request->gioi_tinh,
            'id_vai_tro' => $request->id_vai_tro,
        ];

        if ($request->has('trang_thai') && $currentUser && $currentUser->isAdmin()) {
            if ((int) $currentUser->id === (int) $id) {
                return back()->with('error', 'Không thể tự thay đổi trạng thái của chính mình!');
            }

            if ($user->id_vai_tro != 2) {
                $updateData['trang_thai'] = (int)$request->trang_thai;
            }
        }

        $user->update($updateData);

        return redirect()->route('admin.user.index', $request->query())
            ->with('success', 'Cập nhật người dùng thành công!');
    }

    public function destroy(Request $request, $id)
    {
        $currentUser = auth()->user();

        if (!$currentUser->isAdmin()) {
            return redirect()->route('admin.user.index', $request->query())
                ->with('error', 'Chỉ Quản trị viên (ADMIN) mới có quyền xóa tài khoản người dùng!');
        }

        if ($currentUser->id == $id) {
            return redirect()->route('admin.user.index', $request->query())
                ->with('error', 'Bạn không thể tự xóa tài khoản của chính mình!');
        }

        $user = NguoiDung::findOrFail($id);
        if ($user->id_vai_tro == 2) {
            return redirect()->route('admin.user.index', $request->query())
                ->with('error', 'Không thể xóa tài khoản Quản trị viên!');
        }

        $totalOrders = $user->donHangs()->count();

        if ($totalOrders > 0) {
            return redirect()->route('admin.user.index', $request->query())
                ->with('error', 'Không thể xóa người dùng đang có đơn hàng trong hệ thống!');
        }

        DB::transaction(function () use ($user) {
            $gioHang = $user->gioHang()->first();
            if ($gioHang) {
                $gioHang->ctGioHangs()->delete();
                $gioHang->delete();
            }

            $user->delete();
        });

        return redirect()->route('admin.user.index', $request->query())
            ->with('success', 'Xóa người dùng thành công!');
    }

}