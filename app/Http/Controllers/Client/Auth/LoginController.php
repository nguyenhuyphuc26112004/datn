<?php

namespace App\Http\Controllers\Client\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\NguoiDung;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (session('idNguoiDung')) {
            return redirect()->route('client.home');
        }
        return view('client.auth.dangNhap');
    }

    public function login(Request $request)
    {
        $request->validate([
            'tenDangNhap' => 'required|string',
            'matKhau'     => 'required|string|min:8|max:32',
        ], [
            'tenDangNhap.required' => 'Tên đăng nhập không được để trống.',
            'matKhau.required'     => 'Mật khẩu không được để trống.',
            'matKhau.min'          => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'matKhau.max'          => 'Mật khẩu không được vượt quá 32 ký tự.',
        ]);

        $tenDangNhap = trim($request->input('tenDangNhap'));
        $matKhau     = trim($request->input('matKhau'));

        $user = NguoiDung::where('ten_dang_nhap', $tenDangNhap)->first();

        if (!$user) {
            return back()->with('errorLogin', 'Tên đăng nhập hoặc mật khẩu không tồn tại!');
        }

        if ($user->isLocked()) {
            return back()->with('errorLogin', 'Tài khoản của bạn đã bị khóa bởi Quản trị viên. Vui lòng liên hệ bộ phận hỗ trợ để biết thêm chi tiết!');
        }

        $now = Carbon::now();
        if (!empty($user->thoi_gian_khoa) && Carbon::parse($user->thoi_gian_khoa)->gt($now)) {
            $giayConLai = Carbon::parse($user->thoi_gian_khoa)->diffInSeconds($now);
            return back()->with('errorLogin', "Tài khoản của bạn đang bị khóa. Thử lại sau {$giayConLai} giây.");
        }

        if (!empty($user->thoi_gian_khoa) && Carbon::parse($user->thoi_gian_khoa)->lte($now)) {
            $user->update([
                'so_lan_sai' => 0,
                'thoi_gian_khoa' => null,
            ]);
            $user->refresh();
        }

        if (Hash::check($matKhau, $user->mat_khau)) {
            $user->update([
                'so_lan_sai' => 0
            ]);

            $request->session()->regenerate();
            session([
                'tenDangNhap' => $user->ten_dang_nhap,
                'idNguoiDung' => $user->id,
                'vaiTro' => $user->id_vai_tro
            ]);

            return redirect()->route('client.home');
        } else {
            $soLanSai = $user->so_lan_sai + 1;

            if ($soLanSai >= 3) {
                $khoaDen = Carbon::now()->addMinutes(3);

                $user->update([
                    'so_lan_sai' => $soLanSai,
                    'thoi_gian_khoa' => $khoaDen
                ]);

                return back()->with('errorLogin', 'Bạn đã nhập sai 3 lần. Tài khoản bị khóa trong 3 phút!');
            } else {
                $user->update([
                    'so_lan_sai' => $soLanSai
                ]);

                return back()->with('errorLogin', "Tên đăng nhập hoặc mật khẩu không chính xác. (Sai {$soLanSai}/3 lần)");
            }
        }
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('client.dangNhap')->with('successLogin', 'Đăng xuất thành công!');
    }
}