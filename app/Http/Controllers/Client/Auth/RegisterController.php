<?php

namespace App\Http\Controllers\Client\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\NguoiDung;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('client.auth.dangky');
    }

    public function register(Request $request)
    {
        $request->validate([
            'hoVaTen' => 'required',
            'email' => 'required|email|unique:nguoi_dung,email',
            'tenDangNhap' => 'required|unique:nguoi_dung,ten_dang_nhap',
            'matKhau' => 'required|min:8|max:32',
            'nhapLaiMatKhau' => 'required|same:matKhau',
            'gioiTinh' => 'required|in:nam,nu',
        ], [
            'hoVaTen.required' => 'Họ và tên không được để trống',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email này đã được đăng ký!',
            'tenDangNhap.required' => 'Tên đăng nhập không được để trống',
            'tenDangNhap.unique' => 'Tên đăng nhập đã tồn tại!',
            'matKhau.required' => 'Mật khẩu không được để trống',
            'matKhau.min' => 'Mật khẩu tối thiểu 8 ký tự',
            'matKhau.max' => 'Mật khẩu tối đa không quá 32 ký tự',
            'nhapLaiMatKhau.required' => 'Mật khẩu xác nhận không được để trống',
            'nhapLaiMatKhau.same' => 'Mật khẩu nhập lại không khớp',
            'gioiTinh.required' => 'Vui lòng chọn giới tính',
            'gioiTinh.in' => 'Giới tính không hợp lệ',
        ]);

        $hoVaTen = trim($request->input('hoVaTen'));
        $email = trim($request->input('email'));
        $tenDangNhap = trim($request->input('tenDangNhap'));
        $matKhau = $request->input('matKhau');
        
        $gioiTinhInput = $request->input('gioiTinh', 'nam');
        $gioiTinh = ($gioiTinhInput === 'nu') ? 'Nữ' : 'Nam'; 

        $created = NguoiDung::create([
            'id_vai_tro' => 1,
            'ho_ten' => $hoVaTen,
            'gioi_tinh' => $gioiTinh,
            'email' => $email,
            'ten_dang_nhap' => $tenDangNhap,
            'mat_khau' => Hash::make($matKhau),
            'so_lan_sai' => 0,
            'thoi_gian_khoa' => null,
        ]);

        if ($created) {
            return redirect()->route('client.dangNhap')->with('successRegister', 'Đăng ký thành công! Vui lòng đăng nhập.');
        } 
    }
}