<?php

namespace App\Http\Controllers;

use App\Mail\OrderStatusMail;
use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\Food;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // ===================== DASHBOARD =====================
    public function dashboard()
    {
        $totalOrders = Bill::count();
        $totalRevenue = Bill::sum('total');
        $totalProducts = Food::count();
        $totalUsers = User::count();
        $recentOrders = Bill::with('customer')->orderBy('created_at', 'desc')->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalOrders', 'totalRevenue', 'totalProducts', 'totalUsers', 'recentOrders'
        ));
    }

    // ===================== QUẢN LÝ SẢN PHẨM =====================
    public function foodList(Request $request)
    {
        $query = Food::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $foods = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $categories = Food::getCategories();
        return view('admin.food.list', compact('foods', 'categories'));
    }

    public function foodCreate()
    {
        $categories = Food::getCategories();
        return view('admin.food.create', compact('categories'));
    }

    public function foodStore(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:200',
            'price'      => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'category'   => 'required|in:ao_nam,ao_nu,quan_nam,quan_nu,vay_dam,phu_kien',
            'stock'      => 'required|integer|min:0',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ], [
            'name.required'     => 'Vui lòng nhập tên sản phẩm',
            'price.required'    => 'Vui lòng nhập giá',
            'category.required' => 'Vui lòng chọn danh mục',
        ]);

        $data = $request->only(['name', 'description', 'price', 'sale_price', 'category', 'stock']);
        $data['slug'] = Str::slug($request->name) . '-' . time();
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $data['status'] = $request->has('status') ? 1 : 0;

        if ($request->hasFile('image')) {
            $imageDir = public_path('images/foods');
            if (!file_exists($imageDir)) {
                mkdir($imageDir, 0755, true);
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move($imageDir, $imageName);
            $data['image'] = 'images/foods/' . $imageName;
        }

        Food::create($data);

        return redirect()->route('admin.food.list')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function foodEdit($id)
    {
        $food = Food::findOrFail($id);
        $categories = Food::getCategories();
        return view('admin.food.edit', compact('food', 'categories'));
    }

    public function foodUpdate(Request $request, $id)
    {
        $food = Food::findOrFail($id);

        $request->validate([
            'name'       => 'required|string|max:200',
            'price'      => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'category'   => 'required|in:ao_nam,ao_nu,quan_nam,quan_nu,vay_dam,phu_kien',
            'stock'      => 'required|integer|min:0',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'price', 'sale_price', 'category', 'stock']);
        $data['slug'] = Str::slug($request->name) . '-' . $id;
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $data['status'] = $request->has('status') ? 1 : 0;

        if ($request->hasFile('image')) {
            if ($food->image && file_exists(public_path($food->image))) {
                unlink(public_path($food->image));
            }
            $imageDir = public_path('images/foods');
            if (!file_exists($imageDir)) {
                mkdir($imageDir, 0755, true);
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move($imageDir, $imageName);
            $data['image'] = 'images/foods/' . $imageName;
        }

        $food->update($data);

        return redirect()->route('admin.food.list')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function foodDelete($id)
    {
        $food = Food::findOrFail($id);
        if ($food->image && file_exists(public_path($food->image))) {
            unlink(public_path($food->image));
        }
        $food->delete();

        return redirect()->route('admin.food.list')->with('success', 'Xóa sản phẩm thành công!');
    }

    // ===================== QUẢN LÝ ĐƠN HÀNG =====================
    public function orderList(Request $request)
    {
        $query = Bill::with('customer');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone_number', 'like', '%' . $request->search . '%');
            });
        }
        $orders = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('admin.order.list', compact('orders'));
    }

    public function orderDetail($id)
    {
        $order = Bill::with(['customer', 'details.food'])->findOrFail($id);
        return view('admin.order.detail', compact('order'));
    }

    public function orderUpdateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,shipping,delivered,cancelled']);
        $order = Bill::with('customer')->findOrFail($id);
        $order->update(['status' => $request->status]);

        // Gửi email thông báo cập nhật trạng thái
        if ($order->customer && $order->customer->email) {
            try {
                Mail::to($order->customer->email)->send(new OrderStatusMail($order));
            } catch (\Exception $e) {
                Log::error('Failed to send order status email', [
                    'bill_id' => $order->id,
                    'email'   => $order->customer->email,
                    'error'   => $e->getMessage(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    public function orderDelete($id)
    {
        $order = Bill::findOrFail($id);
        $order->details()->delete();
        $order->delete();

        return redirect()->route('admin.order.list')->with('success', 'Xóa đơn hàng thành công!');
    }

    // ===================== QUẢN LÝ NGƯỜI DÙNG =====================
    public function userList(Request $request)
    {
        $query = User::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }
        $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('admin.user.list', compact('users'));
    }

    public function userCreate()
    {
        return view('admin.user.create');
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'level'    => 'required|in:1,2,3',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:500',
        ], [
            'email.unique'       => 'Email đã tồn tại',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'level'    => $request->level,
            'phone'    => $request->phone,
            'address'  => $request->address,
        ]);

        return redirect()->route('admin.user.list')->with('success', 'Thêm người dùng thành công!');
    }

    public function userEdit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }

    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $id,
            'level'   => 'required|in:1,2,3',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $data = $request->only(['name', 'email', 'level', 'phone', 'address']);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.user.list')->with('success', 'Cập nhật người dùng thành công!');
    }

    public function userDelete($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Không thể xóa tài khoản đang đăng nhập!');
        }
        $user->delete();

        return redirect()->route('admin.user.list')->with('success', 'Xóa người dùng thành công!');
    }
}
