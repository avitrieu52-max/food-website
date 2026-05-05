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

class PageController extends Controller
{
    public function getIndex()
    {
        // Lấy slides từ DB
        $slides = Slide::where('is_active', true)->orderBy('order', 'asc')->get();

        // Lấy sản phẩm mới (dựa trên ngày tạo)
        $new_products = Food::orderBy('created_at', 'desc')->limit(8)->get();

        // Lấy sản phẩm nổi bật (đề nghị)
        $top_products = Food::where('is_featured', true)->limit(8)->get();

        // Lấy sản phẩm khuyến mãi
        $promotion_products = Food::whereNotNull('sale_price')
            ->where('sale_price', '>', 0)
            ->limit(4)->get();

        // Tất cả sản phẩm
        $all_products = Food::where('status', true)->orderBy('created_at', 'desc')->limit(8)->get();

        return view('foods.index', compact('slides', 'new_products', 'top_products', 'promotion_products', 'all_products'));
    }

    public function getChiTiet($id)
    {
        $food = Food::with('category')->findOrFail($id);
        $relatedProducts = Food::with('category')
            ->where('category_id', $food->category_id)
            ->where('id', '!=', $food->id)
            ->limit(4)->get();

        return view('foods.show', compact('food', 'relatedProducts'));
    }

    public function search(Request $request)
    {
        $keyword = $request->get('q', '');
        $query = Food::where('status', true);

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('description', 'like', '%' . $keyword . '%');
            });
        }

        $foods = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();
        $categories = Food::getCategories();

        return view('foods.search', compact('foods', 'categories', 'keyword'));
    }

    public function getCart()
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);

        return view('cart', [
            'cart' => $cart,
            'productCarts' => $cart->items,
        ]);
    }

    public function addToCart(Request $request, $id)
    {
        $product = Food::findOrFail($id);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($product, $id);

        $request->session()->put('cart', $cart);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'totalQty' => $cart->totalQty,
                'message'  => 'Đã thêm vào giỏ hàng!',
            ]);
        }

        return redirect()->back()->with('success', 'Thêm sản phẩm vào giỏ hàng thành công.');
    }

    public function updateCart(Request $request, $id)
    {
        $qty = (int) $request->input('qty', 1);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);

        if ($qty <= 0) {
            $cart->removeItem($id);
        } else {
            $cart->updateQty($id, $qty);
        }

        if (count($cart->items) > 0) {
            Session::put('cart', $cart);
        } else {
            Session::forget('cart');
        }

        return redirect()->route('banhang.giohang')->with('success', 'Cập nhật giỏ hàng thành công.');
    }

    public function delCartItem($id)
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->removeItem($id);

        if (count($cart->items) > 0) {
            Session::put('cart', $cart);
        } else {
            Session::forget('cart');
        }

        return redirect()->route('banhang.giohang')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    public function getSignin()
    {
        return view('dangky');
    }

    public function postSignin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|max:20',
            'fullname' => 'required',
            'repassword' => 'required|same:password',
        ],[
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Không đúng định dạng email',
            'email.unique' => 'Email đã có người sử dụng',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'repassword.same' => 'Mật khẩu không giống nhau',
            'password.min' => 'Mật khẩu ít nhất 6 ký tự',
        ]);

        $user = new User();
        $user->name = $request->fullname;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->level = 3;
        $user->save();

        return redirect()->back()->with('success', 'Tạo tài khoản thành công');
    }

    public function getLogin()
    {
        return view('login');
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|max:20',
        ],[
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Không đúng định dạng email',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu ít nhất 6 ký tự',
        ]);

        $credentials = ['email' => $request->email, 'password' => $request->password];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Admin hoặc Manager → vào trang quản trị
            if (in_array($user->level, [1, 2])) {
                return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công!');
            }

            // Khách hàng → về trang chủ
            return redirect()->route('banhang.index')->with(['flag' => 'alert', 'message' => 'Đăng nhập thành công']);
        }

        return redirect()->back()->with(['flag' => 'danger', 'message' => 'Email hoặc mật khẩu không đúng']);
    }

    public function getLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('getlogin');
    }

    public function getCheckout()
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);

        $shippingFeeService = new ShippingFeeService();
        $shippingFee = $shippingFeeService->calculate($cart->totalPrice);

        $appliedCoupon = Session::get('applied_coupon');
        $discountAmount = $appliedCoupon['discount_amount'] ?? 0;
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

    public function postCheckout(Request $request)
    {
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);

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
        $shippingFee = $shippingFeeService->calculate($cart->totalPrice);

        // Xử lý coupon
        $appliedCoupon  = Session::get('applied_coupon');
        $discountAmount = 0;
        $couponCode     = null;

        if ($appliedCoupon) {
            $couponService = new CouponService();
            $result = $couponService->validate($appliedCoupon['code'], $cart->totalPrice);
            if ($result['valid']) {
                $discountAmount = $couponService->calculateDiscount($result['coupon'], $cart->totalPrice);
                $couponCode     = $result['coupon']->code;
                // Tăng used_count
                $result['coupon']->increment('used_count');
            }
        }

        $finalTotal = $cart->totalPrice + $shippingFee - $discountAmount;

        $customer = Customer::create([
            'name'         => $request->input('name'),
            'gender'       => $request->input('gender'),
            'email'        => $request->input('email'),
            'address'      => $request->input('address'),
            'phone_number' => $request->input('phone_number'),
            'note'         => $request->input('notes'),
        ]);

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

        foreach ($cart->items as $productId => $item) {
            BillDetail::create([
                'id_bill'    => $bill->id,
                'id_product' => $productId,
                'quantity'   => $item['qty'],
                'unit_price' => $item['price'] / $item['qty'],
            ]);
        }

        Session::forget('cart');
        Session::forget('applied_coupon');

        // Gửi email xác nhận
        try {
            $bill->load(['details.food', 'customer']);
            Mail::to($customer->email)->send(new OrderConfirmationMail($bill));
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email', [
                'bill_id' => $bill->id,
                'email'   => $customer->email,
                'error'   => $e->getMessage(),
            ]);
        }

        return redirect()->route('home')->with('success', 'Đặt hàng thành công! Cảm ơn bạn đã mua hàng. Mã đơn hàng: #' . $bill->id);
    }
}
