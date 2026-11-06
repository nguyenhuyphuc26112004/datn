<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminOrStaff
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Chưa đăng nhập -> Văng về trang đăng nhập
        if (!Auth::check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Vui lòng đăng nhập để tiếp tục!');
        }

        $user = Auth::user();

        // 2. Kiểm tra nếu tài khoản bị khóa
        if ($user->isLocked()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')
                ->with('error', 'Tài khoản của bạn đã bị khóa bởi Quản trị viên!');
        }

        // 3. Kiểm tra nếu không phải Admin hoặc Staff (Khách hàng thường cố tình vào)
        if (!$user->isStaffOrAdmin()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')
                ->with('error', 'Tài khoản của bạn không có quyền truy cập trang Quản trị!');
        }

        // 4. Hợp lệ -> Cho đi tiếp
        return $next($request);
    }
}