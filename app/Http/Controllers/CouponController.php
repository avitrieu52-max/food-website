<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CouponController extends Controller
{
    protected CouponService $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function apply(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string']);

        $cart = Session::has('cart') ? Session::get('cart') : null;
        if (!$cart || $cart->totalQty === 0) {
            return response()->json(['success' => false, 'error' => 'Giỏ hàng trống.']);
        }

        $result = $this->couponService->validate($request->coupon_code, $cart->totalPrice);

        if (!$result['valid']) {
            return response()->json(['success' => false, 'error' => $result['error']]);
        }

        $coupon          = $result['coupon'];
        $discountAmount  = $this->couponService->calculateDiscount($coupon, $cart->totalPrice);

        Session::put('applied_coupon', [
            'code'            => $coupon->code,
            'discount_amount' => $discountAmount,
        ]);

        return response()->json([
            'success'         => true,
            'discount_amount' => $discountAmount,
            'new_total'       => $cart->totalPrice - $discountAmount,
        ]);
    }

    public function remove()
    {
        Session::forget('applied_coupon');
        return response()->json(['success' => true]);
    }

    // Admin CRUD
    public function adminIndex(Request $request)
    {
        $query = Coupon::query();
        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->search . '%');
        }
        $coupons = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('admin.coupon.list', compact('coupons'));
    }

    public function adminCreate()
    {
        return view('admin.coupon.create');
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'code'            => 'required|string|max:50|unique:coupons,code',
            'discount_type'   => 'required|in:percent,fixed',
            'discount_value'  => 'required|numeric|min:0',
            'min_order_value' => 'required|numeric|min:0',
            'max_uses'        => 'nullable|integer|min:1',
            'expires_at'      => 'nullable|date',
        ], [
            'code.required'   => 'Vui lòng nhập mã giảm giá',
            'code.unique'     => 'Mã giảm giá đã tồn tại',
            'discount_type.required' => 'Vui lòng chọn loại giảm giá',
            'discount_value.required' => 'Vui lòng nhập giá trị giảm',
        ]);

        Coupon::create([
            'code'            => strtoupper($request->code),
            'discount_type'   => $request->discount_type,
            'discount_value'  => $request->discount_value,
            'min_order_value' => $request->min_order_value,
            'max_uses'        => $request->max_uses,
            'expires_at'      => $request->expires_at,
            'is_active'       => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.coupon.list')->with('success', 'Thêm mã giảm giá thành công!');
    }

    public function adminEdit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupon.edit', compact('coupon'));
    }

    public function adminUpdate(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code'            => 'required|string|max:50|unique:coupons,code,' . $id,
            'discount_type'   => 'required|in:percent,fixed',
            'discount_value'  => 'required|numeric|min:0',
            'min_order_value' => 'required|numeric|min:0',
            'max_uses'        => 'nullable|integer|min:1',
            'expires_at'      => 'nullable|date',
        ]);

        $coupon->update([
            'code'            => strtoupper($request->code),
            'discount_type'   => $request->discount_type,
            'discount_value'  => $request->discount_value,
            'min_order_value' => $request->min_order_value,
            'max_uses'        => $request->max_uses,
            'expires_at'      => $request->expires_at,
            'is_active'       => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.coupon.list')->with('success', 'Cập nhật mã giảm giá thành công!');
    }

    public function adminDelete($id)
    {
        Coupon::findOrFail($id)->delete();
        return redirect()->route('admin.coupon.list')->with('success', 'Xóa mã giảm giá thành công!');
    }
}
