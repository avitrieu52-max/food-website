<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller xử lý đăng nhập / đăng xuất cho tài khoản admin.
 * Chỉ cho phép người dùng có cấp độ 1 (Admin) hoặc 2 (Manager) đăng nhập vào trang quản trị.
 */
class UserController extends Controller
{
    /**
     * Hiển thị trang đăng nhập admin.
     */
    public function getLogin()
    {
        return view('admin.login');
    }

    /**
     * Xử lý đăng nhập admin.
     * Kiểm tra thông tin đăng nhập và cấp độ quyền truy cập.
     * Nếu là khách hàng (level 3) thì không cho vào admin.
     */
    public function postLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6|max:20',
        ], [
            'email.required'    => 'Vui lòng nhập email',
            'email.email'       => 'Không đúng định dạng email',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min'      => 'Mật khẩu ít nhất 6 ký tự',
        ]);

        $credentials = [
            'email'    => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Tái tạo session để bảo mật
            $user = Auth::user();

            // Chỉ Admin (1) và Manager (2) mới được vào trang quản trị
            if (in_array($user->level, [1, 2])) {
                return redirect()->route('admin.dashboard');
            }

            // Khách hàng (level 3) không có quyền vào admin
            Auth::logout();
            return redirect()->back()->with(['flag' => 'danger', 'message' => 'Bạn không có quyền truy cập admin']);
        }

        return redirect()->back()->with(['flag' => 'danger', 'message' => 'Đăng nhập không thành công']);
    }

    /**
     * Đăng xuất khỏi trang admin.
     * Hủy session và tái tạo CSRF token để bảo mật.
     */
    public function getLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();       // Hủy toàn bộ session
        $request->session()->regenerateToken();  // Tái tạo CSRF token

        return redirect()->route('getlogin');
    }
}
