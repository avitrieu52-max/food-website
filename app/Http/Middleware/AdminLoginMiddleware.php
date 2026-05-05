<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware kiểm tra quyền truy cập trang quản trị.
 * Chỉ cho phép người dùng có cấp độ 1 (Admin) hoặc 2 (Manager) vào khu vực admin.
 */
class AdminLoginMiddleware
{
    /**
     * Xử lý request đến.
     * Nếu người dùng đã đăng nhập và có quyền admin/manager thì cho qua,
     * ngược lại chuyển hướng về trang đăng nhập với thông báo lỗi.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Kiểm tra cấp độ: 1 = Admin, 2 = Manager
            if (in_array($user->level, [1, 2])) {
                return $next($request); // Cho phép tiếp tục
            }
        }

        // Không có quyền → chuyển về trang đăng nhập
        return redirect()->route('getlogin')->with(['flag' => 'danger', 'message' => 'Vui lòng đăng nhập với tài khoản quản trị']);
    }
}
