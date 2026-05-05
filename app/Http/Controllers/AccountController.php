<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Controller quản lý tài khoản khách hàng.
 * Xử lý: xem lịch sử đơn hàng, chi tiết đơn hàng,
 * cập nhật thông tin cá nhân và đổi mật khẩu.
 */
class AccountController extends Controller
{
    /**
     * Trang tài khoản cá nhân.
     * Hiển thị thông tin người dùng và danh sách đơn hàng đã đặt.
     */
    public function index()
    {
        $user = Auth::user();

        // Lấy tất cả đơn hàng của người dùng dựa trên email khớp với bảng customers
        $orders = Bill::whereHas('customer', function ($q) use ($user) {
            $q->where('email', $user->email);
        })->with('customer')->orderBy('created_at', 'desc')->get();

        return view('account.index', compact('user', 'orders'));
    }

    /**
     * Xem chi tiết một đơn hàng cụ thể.
     * Chỉ cho phép xem đơn hàng thuộc về email của người dùng đang đăng nhập.
     */
    public function orderDetail($id)
    {
        $user  = Auth::user();

        // Tải kèm thông tin khách hàng và chi tiết sản phẩm trong đơn
        $order = Bill::with(['customer', 'details.food'])
            ->whereHas('customer', function ($q) use ($user) {
                $q->where('email', $user->email); // Bảo mật: chỉ xem đơn của chính mình
            })
            ->findOrFail($id);

        return view('account.order-detail', compact('order'));
    }

    /**
     * Cập nhật thông tin cá nhân (họ tên, số điện thoại, địa chỉ).
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Vui lòng nhập họ tên',
        ]);

        // Cập nhật thông tin người dùng đang đăng nhập
        Auth::user()->update([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('account.index')->with('success', 'Cập nhật thông tin thành công!');
    }

    /**
     * Đổi mật khẩu tài khoản.
     * Yêu cầu nhập mật khẩu hiện tại để xác minh trước khi đổi.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed', // confirmed = phải có trường new_password_confirmation
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
            'new_password.required'     => 'Vui lòng nhập mật khẩu mới',
            'new_password.min'          => 'Mật khẩu mới ít nhất 6 ký tự',
            'new_password.confirmed'    => 'Xác nhận mật khẩu không khớp',
        ]);

        // Kiểm tra mật khẩu hiện tại có đúng không
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return redirect()->back()->with('password_error', 'Mật khẩu hiện tại không đúng');
        }

        // Lưu mật khẩu mới đã được mã hóa
        Auth::user()->update(['password' => Hash::make($request->new_password)]);

        return redirect()->route('account.index')->with('success', 'Đổi mật khẩu thành công!');
    }
}
