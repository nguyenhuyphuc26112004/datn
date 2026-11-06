<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\NguoiDung;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && in_array(Auth::user()->id_vai_tro, [2, 3])) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.dangNhap');
    }

    public function login(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required|string',
            'mat_khau'      => 'required|string',
        ], [
            'ten_dang_nhap.required' => 'Tên đăng nhập không được để trống.',
            'mat_khau.required'      => 'Mật khẩu không được để trống.',
        ]);
        $user = NguoiDung::where('ten_dang_nhap', $request->ten_dang_nhap)->first();
        if ($user && Hash::check($request->mat_khau, $user->mat_khau)) {
            // Kiểm tra trạng thái tài khoản
            if ($user->isLocked()) {
                return back()->with('error', 'Tài khoản của bạn đã bị khóa! Vui lòng liên hệ Quản trị viên.')->withInput();
            }

            if (!in_array($user->id_vai_tro, [2, 3])) {
                return back()->with('error', 'Tài khoản của bạn không có quyền truy cập trang quản trị!')->withInput();
            }
            Auth::login($user);
            $request->session()->regenerate();

            $message = ($user->id_vai_tro == 2)
                ? 'Đăng nhập thành công với tài khoản Quản trị viên!' 
                : 'Đăng nhập thành công với tài khoản Nhân viên!';

            return redirect()->route('admin.dashboard')->with('success', $message);
        }

        return back()->with('error', 'Tên đăng nhập hoặc mật khẩu không chính xác.')->withInput($request->except('mat_khau'));    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Đăng xuất thành công!');
    }
}