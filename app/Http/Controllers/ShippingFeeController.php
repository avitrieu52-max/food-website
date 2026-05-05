<?php

namespace App\Http\Controllers;

use App\Models\ShippingFee;
use Illuminate\Http\Request;

class ShippingFeeController extends Controller
{
    public function adminIndex()
    {
        $fees = ShippingFee::orderBy('min_order_value', 'desc')->get();
        return view('admin.shipping.list', compact('fees'));
    }

    public function adminCreate()
    {
        return view('admin.shipping.create');
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:100',
            'min_order_value' => 'required|numeric|min:0',
            'fee'             => 'required|numeric|min:0',
        ]);

        ShippingFee::create([
            'name'            => $request->name,
            'min_order_value' => $request->min_order_value,
            'fee'             => $request->fee,
            'is_active'       => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.shipping.list')->with('success', 'Thêm quy tắc phí vận chuyển thành công!');
    }

    public function adminEdit($id)
    {
        $fee = ShippingFee::findOrFail($id);
        return view('admin.shipping.edit', compact('fee'));
    }

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

    public function adminDelete($id)
    {
        $activeCount = ShippingFee::where('is_active', true)->count();
        if ($activeCount <= 1) {
            return redirect()->back()->with('error', 'Phải có ít nhất một quy tắc phí vận chuyển đang hoạt động!');
        }
        ShippingFee::findOrFail($id)->delete();
        return redirect()->route('admin.shipping.list')->with('success', 'Xóa quy tắc thành công!');
    }
}
