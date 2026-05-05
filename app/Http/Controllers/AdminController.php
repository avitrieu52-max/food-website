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

/**
 * Controller chính của trang quản trị (Admin Panel).
 * Xử lý: dashboard thống kê, quản lý sản phẩm, đơn hàng, người dùng.
 */
class AdminController extends Controller
{
    // ===================== DASHBOARD =====================

    /**
     * Trang tổng quan quản trị.
     * Hiển thị các số liệu thống kê: tổng đơn hàng, doanh thu, sản phẩm, người dùng
     * và 5 đơn hàng gần nhất.
     */
    public function dashboard()
    {
        $totalOrders   = Bill::count();                                                    // Tổng số đơn hàng
        $totalRevenue  = Bill::sum('total');                                               // Tổng doanh thu
        $totalProducts = Food::count();                                                    // Tổng số sản phẩm
        $totalUsers    = User::count();                                                    // Tổng số người dùng
        $recentOrders  = Bill::with('customer')->orderBy('created_at', 'desc')->limit(5)->get(); // 5 đơn mới nhất

        return view('admin.dashboard', compact(
            'totalOrders', 'totalRevenue', 'totalProducts', 'totalUsers', 'recentOrders'
        ));
    }

    // ===================== QUẢN LÝ SẢN PHẨM =====================

    /**
     * Danh sách sản phẩm trong admin, hỗ trợ tìm kiếm theo tên.
     * Phân trang 15 sản phẩm mỗi trang.
     */
    public function foodList(Request $request)
    {
        $query = Food::with('category');

        // Tìm kiếm theo tên sản phẩm nếu có từ khóa
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $foods      = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $categories = \App\Models\Category::where('is_active', true)->orderBy('id')->get();
        return view('admin.food.list', compact('foods', 'categories'));
    }

    /**
     * Hiển thị form thêm sản phẩm mới.
     */
    public function foodCreate()
    {
        $categories = \App\Models\Category::where('is_active', true)->orderBy('id')->get();
        return view('admin.food.create', compact('categories'));
    }

    /**
     * Lưu sản phẩm mới vào database.
     * Tự động tạo slug từ tên sản phẩm và xử lý upload ảnh.
     */
    public function foodStore(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:200',
            'price'       => 'required|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:type_products,id',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ], [
            'name.required'        => 'Vui lòng nhập tên sản phẩm',
            'price.required'       => 'Vui lòng nhập giá',
            'category_id.required' => 'Vui lòng chọn danh mục',
        ]);

        $data = $request->only(['name', 'description', 'price', 'sale_price', 'category_id', 'stock']);
        $data['slug']        = \Illuminate\Support\Str::slug($request->name) . '-' . time(); // Slug duy nhất
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0; // Sản phẩm nổi bật
        $data['status']      = $request->has('status') ? 1 : 0;      // Trạng thái hiển thị

        // Xử lý upload ảnh sản phẩm
        if ($request->hasFile('image')) {
            $imageDir = public_path('images/foods');
            if (!file_exists($imageDir)) mkdir($imageDir, 0755, true);
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move($imageDir, $imageName);
            $data['image'] = 'images/foods/' . $imageName;
        }

        Food::create($data);
        return redirect()->route('admin.food.list')->with('success', 'Thêm sản phẩm thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa sản phẩm.
     */
    public function foodEdit($id)
    {
        $food       = Food::findOrFail($id);
        $categories = \App\Models\Category::where('is_active', true)->orderBy('id')->get();
        return view('admin.food.edit', compact('food', 'categories'));
    }

    /**
     * Cập nhật thông tin sản phẩm.
     * Nếu có ảnh mới thì xóa ảnh cũ và lưu ảnh mới.
     */
    public function foodUpdate(Request $request, $id)
    {
        $food = Food::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:200',
            'price'       => 'required|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:type_products,id',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'price', 'sale_price', 'category_id', 'stock']);
        $data['slug']        = \Illuminate\Support\Str::slug($request->name) . '-' . $id;
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $data['status']      = $request->has('status') ? 1 : 0;

        // Nếu có ảnh mới: xóa ảnh cũ rồi lưu ảnh mới
        if ($request->hasFile('image')) {
            if ($food->image && file_exists(public_path($food->image))) {
                unlink(public_path($food->image)); // Xóa file ảnh cũ
            }
            $imageDir = public_path('images/foods');
            if (!file_exists($imageDir)) mkdir($imageDir, 0755, true);
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move($imageDir, $imageName);
            $data['image'] = 'images/foods/' . $imageName;
        }

        $food->update($data);
        return redirect()->route('admin.food.list')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    /**
     * Xóa sản phẩm và file ảnh liên quan.
     */
    public function foodDelete($id)
    {
        $food = Food::findOrFail($id);

        // Xóa file ảnh nếu tồn tại
        if ($food->image && file_exists(public_path($food->image))) {
            unlink(public_path($food->image));
        }
        $food->delete();

        return redirect()->route('admin.food.list')->with('success', 'Xóa sản phẩm thành công!');
    }

    // ===================== QUẢN LÝ ĐƠN HÀNG =====================

    /**
     * Danh sách đơn hàng trong admin.
     * Hỗ trợ lọc theo trạng thái và tìm kiếm theo tên/SĐT khách hàng.
     */
    public function orderList(Request $request)
    {
        $query = Bill::with('customer');

        // Lọc theo trạng thái đơn hàng
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Tìm kiếm theo tên hoặc số điện thoại khách hàng
        if ($request->filled('search')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone_number', 'like', '%' . $request->search . '%');
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('admin.order.list', compact('orders'));
    }

    /**
     * Xem chi tiết đơn hàng kèm thông tin khách hàng và danh sách sản phẩm.
     */
    public function orderDetail($id)
    {
        $order = Bill::with(['customer', 'details.food'])->findOrFail($id);
        return view('admin.order.detail', compact('order'));
    }

    /**
     * Cập nhật trạng thái đơn hàng và gửi email thông báo cho khách hàng.
     * Trạng thái hợp lệ: pending, confirmed, shipping, delivered, cancelled.
     */
    public function orderUpdateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,shipping,delivered,cancelled']);

        $order = Bill::with('customer')->findOrFail($id);
        $order->update(['status' => $request->status]);

        // Gửi email thông báo trạng thái mới cho khách hàng
        if ($order->customer && $order->customer->email) {
            try {
                Mail::to($order->customer->email)->send(new OrderStatusMail($order));
            } catch (\Exception $e) {
                // Ghi log lỗi nhưng không dừng luồng xử lý
                Log::error('Failed to send order status email', [
                    'bill_id' => $order->id,
                    'email'   => $order->customer->email,
                    'error'   => $e->getMessage(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    /**
     * Xóa đơn hàng và toàn bộ chi tiết đơn hàng liên quan.
     */
    public function orderDelete($id)
    {
        $order = Bill::findOrFail($id);
        $order->details()->delete(); // Xóa chi tiết đơn hàng trước
        $order->delete();            // Sau đó xóa đơn hàng

        return redirect()->route('admin.order.list')->with('success', 'Xóa đơn hàng thành công!');
    }

    // ===================== QUẢN LÝ NGƯỜI DÙNG =====================

    /**
     * Danh sách người dùng admin, hỗ trợ tìm kiếm theo tên hoặc email.
     */
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

    /**
     * Hiển thị form thêm người dùng mới.
     */
    public function userCreate()
    {
        return view('admin.user.create');
    }

    /**
     * Lưu người dùng mới vào database.
     * Mật khẩu được mã hóa bằng bcrypt trước khi lưu.
     */
    public function userStore(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'level'    => 'required|in:1,2,3', // 1=Admin, 2=Manager, 3=Customer
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:500',
        ], [
            'email.unique'       => 'Email đã tồn tại',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // Mã hóa mật khẩu
            'level'    => $request->level,
            'phone'    => $request->phone,
            'address'  => $request->address,
        ]);

        return redirect()->route('admin.user.list')->with('success', 'Thêm người dùng thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa thông tin người dùng.
     */
    public function userEdit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Cập nhật thông tin người dùng.
     * Chỉ đổi mật khẩu nếu trường password được điền.
     */
    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $id, // Bỏ qua unique của chính nó
            'level'   => 'required|in:1,2,3',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $data = $request->only(['name', 'email', 'level', 'phone', 'address']);

        // Chỉ cập nhật mật khẩu nếu người dùng nhập mật khẩu mới
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.user.list')->with('success', 'Cập nhật người dùng thành công!');
    }

    /**
     * Xóa người dùng.
     * Không cho phép xóa tài khoản đang đăng nhập.
     */
    public function userDelete($id)
    {
        $user = User::findOrFail($id);

        // Bảo vệ: không tự xóa tài khoản của chính mình
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Không thể xóa tài khoản đang đăng nhập!');
        }

        $user->delete();

        return redirect()->route('admin.user.list')->with('success', 'Xóa người dùng thành công!');
    }
}
