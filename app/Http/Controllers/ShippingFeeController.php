<?php

namespace App\Http\Controllers;

use App\Models\ShippingFee;
use Illuminate\Http\Request;

/**
 * Controller quản lý phí vận chuyển trong trang admin.
 * Phí vận chuyển được tính theo quy tắc: đơn hàng đạt mức tối thiểu nào thì áp dụng phí tương ứng.
 */
class ShippingFeeController extends Controller
{
    /**
     * Danh sách tất cả quy tắc phí vận chuyển, sắp xếp theo giá trị đơn tối thiểu giảm dần.
     */
    public function adminIndex()
    {
        $fees = ShippingFee::orderBy('min_order_value', 'desc')->get();
        return view('admin.shipping.list', compact('fees'));
    }

    /**
     * Hiển thị form thêm quy tắc phí vận chuyển mới.
     */
    public function adminCreate()
    {
        return view('admin.shipping.create');
    }

    /**
     * Lưu quy tắc phí vận chuyển mới vào database.
     */
    public function adminStore(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:100', // Tên quy tắc (VD: "Miễn phí vận chuyển")
            'min_order_value' => 'required|numeric|min:0',  // Giá trị đơn hàng tối thiểu để áp dụng
            'fee'             => 'required|numeric|min:0',  // Số tiền phí vận chuyển (0 = miễn phí)
        ]);

        ShippingFee::create([
            'name'            => $request->name,
            'min_order_value' => $request->min_order_value,
            'fee'             => $request->fee,
            'is_active'       => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.shipping.list')->with('success', 'Thêm quy tắc phí vận chuyển thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa quy tắc phí vận chuyển.
     */
    public function adminEdit($id)
    {
        $fee = ShippingFee::findOrFail($id);
        return view('admin.shipping.edit', compact('fee'));
    }

    /**
     * Cập nhật quy tắc phí vận chuyển.
     */
    public function adminUpdate(Request $request, $id)
    {
        $fee = ShippingFee::findOrFail($id);

        $request->validate([
            'name'            => 'required|string|max:100',
            'min_order_value' => 'required|numeric|min:0',
            'fee'             => 'required|numeric|min:0',
        ]);

        $fee->update([
            'name'            => $request->name,
            'min_order_value' => $request->min_order_value,
            'fee'             => $request->fee,
            'is_active'       => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.shipping.list')->with('success', 'Cập nhật phí vận chuyển thành công!');
    }

    /**
     * Xóa quy tắc phí vận chuyển.
     * Bắt buộc phải còn ít nhất 1 quy tắc đang hoạt động để hệ thống tính phí được.
     */
    public function adminDelete($id)
    {
        // Kiểm tra số lượng quy tắc đang hoạt động
        $activeCount = ShippingFee::where('is_active', true)->count();
        if ($activeCount <= 1) {
            return redirect()->back()->with('error', 'Phải có ít nhất một quy tắc phí vận chuyển đang hoạt động!');
        }

        ShippingFee::findOrFail($id)->delete();
        return redirect()->route('admin.shipping.list')->with('success', 'Xóa quy tắc thành công!');
    }
}
