<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Lấy đơn hàng qua email khớp với bảng customers
        $orders = Bill::whereHas('customer', function ($q) use ($user) {
            $q->where('email', $user->email);
        })->with('customer')->orderBy('created_at', 'desc')->get();

        return view('account.index', compact('user', 'orders'));
    }

    public function orderDetail($id)
    {
        $user  = Auth::user();
        $order = Bill::with(['customer', 'details.food'])
            ->whereHas('customer', function ($q) use ($user) {
                $q->where('email', $user->email);
            })
            ->findOrFail($id);

        return view('account.order-detail', compact('order'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Vui lòng nhập họ tên',
        ]);

        Auth::user()->update([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('account.index')->with('success', 'Cập nhật thông tin thành công!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required',
            'new_password'          => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
            'new_password.required'     => 'Vui lòng nhập mật khẩu mới',
            'new_password.min'          => 'Mật khẩu mới ít nhất 6 ký tự',
            'new_password.confirmed'    => 'Xác nhận mật khẩu không khớp',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return redirect()->back()->with('password_error', 'Mật khẩu hiện tại không đúng');
        }

        Auth::user()->update(['password' => Hash::make($request->new_password)]);

        return redirect()->route('account.index')->with('success', 'Đổi mật khẩu thành công!');
    }
}
