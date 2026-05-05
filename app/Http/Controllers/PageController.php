<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Food;
use App\Models\Slide;
use App\Models\User;
use App\Services\CouponService;
use App\Services\ShippingFeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

/**
 * Controller chính xử lý các trang frontend của website bán hàng.
 * Bao gồm: trang chủ, chi tiết sản phẩm, tìm kiếm, giỏ hàng,
 * thanh toán, đăng ký, đăng nhập, đăng xuất.
 */
class PageController extends Controller
{
    /**
     * Trang chủ website.
     * Hiển thị: slides/banner, sản phẩm mới, sản phẩm nổi bật,
     * sản phẩm khuyến mãi và tất cả sản phẩm.
     */
    public function getIndex()
    {
        // Lấy slides đang hoạt động, sắp xếp theo thứ tự
        $slides = Slide::where('is_active', true)->orderBy('order', 'asc')->get();

        // Sản phẩm mới nhất (8 sản phẩm theo ngày tạo)
        $new_products = Food::orderBy('created_at', 'desc')->limit(8)->get();

        // Sản phẩm nổi bật / đề nghị (được đánh dấu is_featured)
        $top_products = Food::where('is_featured', true)->limit(8)->get();

        // Sản phẩm khuyến mãi (có giá sale_price > 0)
        $promotion_products = Food::whereNotNull('sale_price')
            ->where('sale_price', '>', 0)
            ->limit(4)->get();

        // Tất cả sản phẩm đang hoạt động (8 sản phẩm mới nhất)
        $all_products = Food::where('status', true)->orderBy('created_at', 'desc')->limit(8)->get();

        return view('foods.index', compact('slides', 'new_products', 'top_products', 'promotion_products', 'all_products'));
    }

    /**
     * Trang chi tiết sản phẩm.
     * Hiển thị thông tin đầy đủ và 4 sản phẩm liên quan cùng danh mục.
     */
    public function getChiTiet($id)
    {
        $food = Food::with('category')->findOrFail($id);

        // Lấy sản phẩm liên quan cùng danh mục (trừ sản phẩm hiện tại)
        $relatedProducts = Food::with('category')
            ->where('category_id', $food->category_id)
            ->where('id', '!=', $food->id)
            ->limit(4)->get();

        return view('foods.show', compact('food', 'relatedProducts'));
    }

    /**
     * Trang tìm kiếm sản phẩm.
     * Tìm theo tên và mô tả sản phẩm, phân trang 12 kết quả/trang.
     */
    public function search(Request $request)
    {
        $keyword = $request->get('q', '');
        $query   = Food::where('status', true);

        // Tìm kiếm theo tên hoặc mô tả nếu có từ khóa
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('description', 'like', '%' . $keyword . '%');
            });
        }

        $foods      = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();
        $categories = Food::getCategories();

        return view('foods.search', compact('foods', 'categories', 'keyword'));
    }

    // ===================== GIỎ HÀNG =====================

    /**
     * Hiển thị trang giỏ hàng.
     * Lấy giỏ hàng từ session và truyền vào view.
     */
    public function getCart()
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart    = new Cart($oldCart);

        return view('cart', [
            'cart'         => $cart,
            'productCarts' => $cart->items,
        ]);
    }

    /**
     * Thêm sản phẩm vào giỏ hàng.
     * Hỗ trợ cả AJAX (trả về JSON) và redirect thông thường.
     */
    public function addToCart(Request $request, $id)
    {
        $product = Food::findOrFail($id);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart    = new Cart($oldCart);
        $cart->add($product, $id); // Thêm sản phẩm vào giỏ

        $request->session()->put('cart', $cart); // Lưu giỏ hàng vào session

        // Trả về JSON nếu là AJAX request (dùng cho nút thêm giỏ hàng không reload trang)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'totalQty' => $cart->totalQty,
                'message'  => 'Đã thêm vào giỏ hàng!',
            ]);
        }

        return redirect()->back()->with('success', 'Thêm sản phẩm vào giỏ hàng thành công.');
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng.
     * Nếu số lượng <= 0 thì xóa sản phẩm khỏi giỏ.
     */
    public function updateCart(Request $request, $id)
    {
        $qty     = (int) $request->input('qty', 1);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart    = new Cart($oldCart);

        if ($qty <= 0) {
            $cart->removeItem($id); // Xóa nếu số lượng = 0
        } else {
            $cart->updateQty($id, $qty); // Cập nhật số lượng mới
        }

        // Lưu lại giỏ hàng hoặc xóa session nếu giỏ trống
        if (count($cart->items) > 0) {
            Session::put('cart', $cart);
        } else {
            Session::forget('cart');
        }

        return redirect()->route('banhang.giohang')->with('success', 'Cập nhật giỏ hàng thành công.');
    }

    /**
     * Xóa một sản phẩm khỏi giỏ hàng.
     */
    public function delCartItem($id)
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart    = new Cart($oldCart);
        $cart->removeItem($id);

        // Xóa session nếu giỏ hàng trống
        if (count($cart->items) > 0) {
            Session::put('cart', $cart);
        } else {
            Session::forget('cart');
        }

        return redirect()->route('banhang.giohang')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    // ===================== ĐĂNG KÝ / ĐĂNG NHẬP =====================

    /**
     * Hiển thị trang đăng ký tài khoản khách hàng.
     */
    public function getSignin()
    {
        return view('dangky');
    }

    /**
     * Xử lý đăng ký tài khoản mới.
     * Tạo người dùng với cấp độ 3 (khách hàng).
     */
    public function postSignin(Request $request)
    {
        $request->validate([
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6|max:20',
            'fullname'   => 'required',
            'repassword' => 'required|same:password', // Xác nhận mật khẩu phải trùng
        ], [
            'email.required'    => 'Vui lòng nhập email',
            'email.email'       => 'Không đúng định dạng email',
            'email.unique'      => 'Email đã có người sử dụng',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'repassword.same'   => 'Mật khẩu không giống nhau',
            'password.min'      => 'Mật khẩu ít nhất 6 ký tự',
        ]);

        // Tạo tài khoản khách hàng mới (level = 3)
        $user           = new User();
        $user->name     = $request->fullname;
        $user->email    = $request->email;
        $user->password = Hash::make($request->password); // Mã hóa mật khẩu
        $user->phone    = $request->phone;
        $user->address  = $request->address;
        $user->level    = 3; // Cấp độ khách hàng
        $user->save();

        return redirect()->back()->with('success', 'Tạo tài khoản thành công');
    }

    /**
     * Hiển thị trang đăng nhập.
     */
    public function getLogin()
    {
        return view('login');
    }

    /**
     * Xử lý đăng nhập.
     * Admin/Manager → chuyển vào trang quản trị.
     * Khách hàng → chuyển về trang chủ.
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

        $credentials = ['email' => $request->email, 'password' => $request->password];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Tái tạo session để bảo mật
            $user = Auth::user();

            // Admin (1) hoặc Manager (2) → vào trang quản trị
            if (in_array($user->level, [1, 2])) {
                return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công!');
            }

            // Khách hàng (3) → về trang chủ
            return redirect()->route('banhang.index')->with(['flag' => 'alert', 'message' => 'Đăng nhập thành công']);
        }

        return redirect()->back()->with(['flag' => 'danger', 'message' => 'Email hoặc mật khẩu không đúng']);
    }

    /**
     * Đăng xuất khỏi hệ thống.
     * Hủy session và tái tạo CSRF token.
     */
    public function getLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();      // Hủy toàn bộ session
        $request->session()->regenerateToken(); // Tái tạo CSRF token

        return redirect()->route('getlogin');
    }

    // ===================== THANH TOÁN =====================

    /**
     * Hiển thị trang thanh toán (checkout).
     * Tính phí vận chuyển, áp dụng mã giảm giá và hiển thị tổng tiền cuối.
     */
    public function getCheckout()
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart    = new Cart($oldCart);

        // Tính phí vận chuyển dựa trên tổng giá trị giỏ hàng
        $shippingFeeService = new ShippingFeeService();
        $shippingFee        = $shippingFeeService->calculate($cart->totalPrice);

        // Lấy thông tin mã giảm giá đang áp dụng từ session
        $appliedCoupon  = Session::get('applied_coupon');
        $discountAmount = $appliedCoupon['discount_amount'] ?? 0;

        // Tổng tiền cuối = giỏ hàng + phí vận chuyển - giảm giá
        $finalTotal = $cart->totalPrice + $shippingFee - $discountAmount;

        return view('checkout', [
            'cart'           => $cart,
            'productCarts'   => $cart->items,
            'shippingFee'    => $shippingFee,
            'discountAmount' => $discountAmount,
            'appliedCoupon'  => $appliedCoupon,
            'finalTotal'     => $finalTotal,
        ]);
    }

    /**
     * Xử lý đặt hàng (POST checkout).
     * Tạo bản ghi customer, bill, bill_details.
     * Tăng used_count của coupon nếu có.
     * Gửi email xác nhận đơn hàng cho khách.
     */
    public function postCheckout(Request $request)
    {
        $oldCart = Session::get('cart');
        $cart    = new Cart($oldCart);

        // Kiểm tra giỏ hàng không trống
        if ($cart->totalQty === 0) {
            return redirect()->route('home')->with('success', 'Giỏ hàng trống.');
        }

        $request->validate([
            'name'           => 'required|string|max:255',
            'gender'         => 'nullable|string|in:nam,nữ',
            'email'          => 'required|email|max:255',
            'address'        => 'required|string|max:500',
            'phone_number'   => 'required|string|max:20',
            'payment_method' => 'required|string',
        ]);

        // Tính phí vận chuyển
        $shippingFeeService = new ShippingFeeService();
        $shippingFee        = $shippingFeeService->calculate($cart->totalPrice);

        // Xử lý mã giảm giá nếu có trong session
        $appliedCoupon  = Session::get('applied_coupon');
        $discountAmount = 0;
        $couponCode     = null;

        if ($appliedCoupon) {
            $couponService = new CouponService();
            $result        = $couponService->validate($appliedCoupon['code'], $cart->totalPrice);

            if ($result['valid']) {
                $discountAmount = $couponService->calculateDiscount($result['coupon'], $cart->totalPrice);
                $couponCode     = $result['coupon']->code;
                $result['coupon']->increment('used_count'); // Tăng số lần đã dùng
            }
        }

        // Tổng tiền cuối cùng
        $finalTotal = $cart->totalPrice + $shippingFee - $discountAmount;

        // Tạo bản ghi thông tin khách hàng
        $customer = Customer::create([
            'name'         => $request->input('name'),
            'gender'       => $request->input('gender'),
            'email'        => $request->input('email'),
            'address'      => $request->input('address'),
            'phone_number' => $request->input('phone_number'),
            'note'         => $request->input('notes'),
        ]);

        // Tạo đơn hàng
        $bill = Bill::create([
            'id_customer'     => $customer->id,
            'date_order'      => now()->format('Y-m-d'),
            'total'           => $finalTotal,
            'payment'         => $request->input('payment_method'),
            'note'            => $request->input('notes'),
            'coupon_code'     => $couponCode,
            'discount_amount' => $discountAmount,
            'shipping_fee'    => $shippingFee,
        ]);

        // Tạo chi tiết đơn hàng cho từng sản phẩm trong giỏ
        foreach ($cart->items as $productId => $item) {
            BillDetail::create([
                'id_bill'    => $bill->id,
                'id_product' => $productId,
                'quantity'   => $item['qty'],
                'unit_price' => $item['price'] / $item['qty'], // Giá đơn vị
            ]);
        }

        // Xóa giỏ hàng và mã giảm giá khỏi session sau khi đặt hàng thành công
        Session::forget('cart');
        Session::forget('applied_coupon');

        // Gửi email xác nhận đơn hàng cho khách
        try {
            $bill->load(['details.food', 'customer']);
            Mail::to($customer->email)->send(new OrderConfirmationMail($bill));
        } catch (\Exception $e) {
            // Ghi log lỗi nhưng không dừng luồng xử lý (đơn hàng vẫn được tạo)
            Log::error('Failed to send order confirmation email', [
                'bill_id' => $bill->id,
                'email'   => $customer->email,
                'error'   => $e->getMessage(),
            ]);
        }

        return redirect()->route('home')->with('success', 'Đặt hàng thành công! Cảm ơn bạn đã mua hàng. Mã đơn hàng: #' . $bill->id);
    }
}
