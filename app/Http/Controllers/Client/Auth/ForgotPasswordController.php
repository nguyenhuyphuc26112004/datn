<?php

namespace App\Http\Controllers\Client\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NguoiDung;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\SendOtpResetPassword;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function showFormQuenMatKhau()
    {
        return view('client.auth.quenMatKhau');
    }

    public function guiMaOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:nguoi_dung,email'
        ], [
            'email.required' => 'Vui lòng nhập email!',
            'email.email' => 'Định dạng email không hợp lệ!',
            'email.exists' => 'Email này không tồn tại trong hệ thống!'
        ]);

        $user = NguoiDung::where('email', $request->email)->first();

        $otp = (string) random_int(100000, 999999);
        $user->ma_otp = $otp;
        $user->het_han_otp = Carbon::now()->addMinutes(10);
        $user->save();
        session()->put([
            'otp_attempts' => 0,
            'reset_email'  => $user->email
        ]);
        // Gửi mail OTP
        try {
            Mail::to($user->email)->send(new SendOtpResetPassword($otp, $user->ho_ten));
            return redirect()->route('client.xacNhanOtp');
        } catch (\Exception $e) {
            return back()->with('error', "Không thể gửi mail, vui lòng thử lại sau! Lỗi: ".$e->getMessage());
        }
    }

    // Hiển thị màn hình nhập OTP & Mật khẩu mới
    public function showFormXacNhanOtp()
    {
        if (!session('reset_email')) {
            return redirect()->route('client.quenMatKhau');
        }
        return view('client.auth.xac_nhan_otp');
    }

    public function datLaiMatKhau(Request $request)
    {
        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('client.quenMatKhau');
        }
        $request->validate([
            'otp' => 'required|string|size:6',
            'new_password' => 'required|min:8|max:32|confirmed'
        ], [
            'otp.required' => 'Vui lòng nhập mã OTP!',
            'otp.size' => 'Mã OTP phải bao gồm 6 chữ số!',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới!',
            'new_password.min' => 'Mật khẩu phải từ 8 ký tự trở lên!',
            'new_password.max' => 'Mật khẩu tối đa không quá 32 ký tự!',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp!'
        ]);

        $user = NguoiDung::where('email', $email)->first();

        if (Carbon::now()->gt(Carbon::parse($user->het_han_otp))) {
            return redirect()->route('client.quenMatKhau')->with('error', 'Mã OTP đã hết hạn! Vui lòng yêu cầu mã mới.');
        }
        if ($user->ma_otp !== $request->otp) {
            $attempts = (int) session('otp_attempts', 0) + 1;
            session()->put('otp_attempts', $attempts);

            if ($attempts >= 5) {
                session()->forget(['reset_email', 'otp_attempts']);
                return redirect()->route('client.quenMatKhau')
                    ->with('error', 'Bạn đã nhập sai OTP quá 5 lần. Vui lòng yêu cầu mã mới!');
            }

            $conLai = 5 - $attempts;
            return back()->with('error', "Mã OTP không chính xác! (Còn {$conLai} lần nhập)");
        }

        $user->mat_khau = Hash::make($request->new_password);
        $user->ma_otp = null;
        $user->het_han_otp = null;
        $user->so_lan_sai = 0;
        $user->thoi_gian_khoa = null;
        $user->save();

        session()->forget(['reset_email', 'otp_attempts']);

        return redirect()->route('client.dangNhap')->with('success', 'Đổi mật khẩu thành công! Vui lòng đăng nhập lại.');
    }
}