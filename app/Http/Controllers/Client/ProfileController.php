<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NguoiDung;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function showProfile()
    {
        if (!session()->has('idNguoiDung')) {
            return redirect()->route('client.dangNhap')->with('errorLogin', 'Vui lòng đăng nhập!');
        }
        $idNguoiDung = session('idNguoiDung');
        $user = NguoiDung::findOrFail($idNguoiDung);

        return view('client.hoSo', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        if (!session()->has('idNguoiDung')) {
            return redirect()->route('client.dangNhap')->with('errorLogin', 'Vui lòng đăng nhập!');
        }
        $idNguoiDung = session('idNguoiDung');
        $request->validate([
            'hoVaTen'  => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:nguoi_dung,email,' . $idNguoiDung,
        ], [
            'hoVaTen.required'  => 'Họ và tên không được để trống!',
            'email.required'    => 'Email không được để trống!',
            'email.email'       => 'Định dạng Email không hợp lệ!',
            'email.unique'      => 'Email này đã được sử dụng bởi tài khoản khác!',
        ]);
        $user = NguoiDung::findOrFail($idNguoiDung);
        $user->update([
            'ho_ten'    => trim($request->hoVaTen),
            'email'     => trim($request->email),
            'gioi_tinh' => $request->gioiTinh,
        ]);
        return redirect()->back()->with('successMsg', 'Cập nhật thông tin thành công!');
    }
    
    public function showDoiMatKhau()
    {
        if (!session()->has('idNguoiDung')) {
            return redirect()->route('client.dangNhap')->with('errorLogin', 'Vui lòng đăng nhập!');
        }

        $idNguoiDung = session('idNguoiDung');
        $user = NguoiDung::findOrFail($idNguoiDung);

        return view('client.doiMatKhau', compact('user'));
    }

    public function postDoiMatKhau(Request $request)
    {
        if (!session()->has('idNguoiDung')) {
            return redirect()->route('client.dangNhap')->with('errorLogin', 'Vui lòng đăng nhập!');
        }

        $request->validate([
            'mat_khau' => 'required',
            'matKhaumoi' => 'required|min:8|max:32|different:mat_khau',
            'nhapLaiMK' => 'required|same:matKhaumoi',
        ], [
            'mat_khau.required' => 'Mật khẩu hiện tại không được để trống!',
            'matKhaumoi.required' => 'Mật khẩu mới không được để trống!',
            'matKhaumoi.min' => 'Mật khẩu mới phải có tối thiểu 8 ký tự!',
            'matKhaumoi.max' => 'Mật khẩu mới không được vượt quá 32 ký tự!',
            'matKhaumoi.different' => 'Mật khẩu mới không được giống mật khẩu cũ!',
            'nhapLaiMK.required' => 'Vui lòng xác nhận lại mật khẩu mới!',
            'nhapLaiMK.same' => 'Mật khẩu nhập lại không khớp!',
        ]);

        $user = NguoiDung::findOrFail(session('idNguoiDung'));
        if (!Hash::check($request->mat_khau, $user->mat_khau)) {
            return back()->withErrors(['mat_khau' => 'Mật khẩu hiện tại không chính xác!'])->withInput();
        }
        $user->update(['mat_khau' => Hash::make($request->matKhaumoi)]);

        session()->flush();

        return redirect()->route('client.dangNhap')->with('successMsg', 'Đổi mật khẩu thành công! Vui lòng đăng nhập lại.');
    }
}