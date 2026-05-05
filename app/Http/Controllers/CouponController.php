<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Controller quản lý mã giảm giá (Coupon).
 * - Phía khách hàng: áp dụng / xóa mã giảm giá trong giỏ hàng (AJAX).
 * - Phía admin: CRUD mã giảm giá.
 */
class CouponController extends Controller
{
    /** Service xử lý logic kiểm tra và tính toán mã giảm giá */
    protected CouponService $couponService;

    /**
     * Inject CouponService qua constructor (Dependency Injection).
     */
    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    // ===================== PHÍA KHÁCH HÀNG =====================

    /**
     * Áp dụng mã giảm giá vào giỏ hàng (trả về JSON cho AJAX).
     * Kiểm tra tính hợp lệ của mã và lưu thông tin giảm giá vào session.
     */
    public function apply(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string']);

        // Lấy giỏ hàng từ session
        $cart = Session::has('cart') ? Session::get('cart') : null;
        if (!$cart || $cart->totalQty === 0) {
            return response()->json(['success' => false, 'error' => 'Giỏ hàng trống.']);
        }

        // Kiểm tra mã giảm giá có hợp lệ không
        $result = $this->couponService->validate($request->coupon_code, $cart->totalPrice);

        if (!$result['valid']) {
            return response()->json(['success' => false, 'error' => $result['error']]);
        }

        // Tính số tiền được giảm
        $coupon         = $result['coupon'];
        $discountAmount = $this->couponService->calculateDiscount($coupon, $cart->totalPrice);

        // Lưu thông tin mã đã áp dụng vào session
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

    /**
     * Xóa mã giảm giá đang áp dụng khỏi session (AJAX).
     */
    public function remove()
    {
        Session::forget('applied_coupon');
        return response()->json(['success' => true]);
    }

    // ===================== PHÍA ADMIN =====================

    /**
     * Danh sách tất cả mã giảm giá, hỗ trợ tìm kiếm theo mã.
     */
    public function adminIndex(Request $request)
    {
        $query = Coupon::query();
        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->search . '%');
        }
        $coupons = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('admin.coupon.list', compact('coupons'));
    }

    /**
     * Hiển thị form thêm mã giảm giá mới.
     */
    public function adminCreate()
    {
        return view('admin.coupon.create');
    }

    /**
     * Lưu mã giảm giá mới vào database.
     * Mã được tự động chuyển thành chữ hoa (UPPERCASE).
     */
    public function adminStore(Request $request)
    {
        $request->validate([
            'code'            => 'required|string|max:50|unique:coupons,code', // Mã phải duy nhất
            'discount_type'   => 'required|in:percent,fixed',                  // Loại: % hoặc số tiền cố định
            'discount_value'  => 'required|numeric|min:0',                     // Giá trị giảm
            'min_order_value' => 'required|numeric|min:0',                     // Đơn hàng tối thiểu
            'max_uses'        => 'nullable|integer|min:1',                     // Giới hạn lượt dùng (null = không giới hạn)
            'expires_at'      => 'nullable|date',                              // Ngày hết hạn
        ], [
            'code.required'           => 'Vui lòng nhập mã giảm giá',
            'code.unique'             => 'Mã giảm giá đã tồn tại',
            'discount_type.required'  => 'Vui lòng chọn loại giảm giá',
            'discount_value.required' => 'Vui lòng nhập giá trị giảm',
        ]);

        Coupon::create([
            'code'            => strtoupper($request->code), // Lưu mã dạng chữ hoa
            'discount_type'   => $request->discount_type,
            'discount_value'  => $request->discount_value,
            'min_order_value' => $request->min_order_value,
            'max_uses'        => $request->max_uses,
            'expires_at'      => $request->expires_at,
            'is_active'       => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.coupon.list')->with('success', 'Thêm mã giảm giá thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa mã giảm giá.
     */
    public function adminEdit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupon.edit', compact('coupon'));
    }

    /**
     * Cập nhật thông tin mã giảm giá.
     */
    public function adminUpdate(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code'            => 'required|string|max:50|unique:coupons,code,' . $id, // Bỏ qua unique của chính nó
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

    /**
     * Xóa mã giảm giá.
     */
    public function adminDelete($id)
    {
        Coupon::findOrFail($id)->delete();
        return redirect()->route('admin.coupon.list')->with('success', 'Xóa mã giảm giá thành công!');
    }
}
