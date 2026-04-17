<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PageController extends Controller
{
    public function getIndex()
    {
        // Lấy sản phẩm mới (dựa trên ngày tạo)
        $new_products = Food::orderBy('created_at', 'desc')->paginate(8);

        // Lấy sản phẩm nổi bật (đề nghị)
        $top_products = Food::where('is_featured', true)->paginate(8);

        // Lấy sản phẩm khuyến mãi
        $promotion_products = Food::whereNotNull('sale_price')
            ->where('sale_price', '>', 0)
            ->paginate(4);

        return view('foods.index', compact('new_products', 'top_products', 'promotion_products'));
    }

    public function getChiTiet($id)
    {
        $food = Food::findOrFail($id);
        $relatedProducts = Food::where('category', $food->category)
            ->where('id', '!=', $food->id)
            ->limit(4)
            ->get();

        return view('foods.show', compact('food', 'relatedProducts'));
    }

    public function addToCart(Request $request, $id)
    {
        $product = Food::findOrFail($id);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($product, $id);

        $request->session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Thêm sản phẩm vào giỏ hàng thành công.');
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

        return redirect()->back();
    }

    public function getCheckout()
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);

        return view('checkout', [
            'cart' => $cart,
            'productCarts' => $cart->items,
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
            'name' => 'required|string|max:255',
            'gender' => 'nullable|string|in:nam,nữ',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'phone_number' => 'required|string|max:20',
            'payment_method' => 'required|string',
        ]);

        $customer = Customer::create([
            'name' => $request->input('name'),
            'gender' => $request->input('gender'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'phone_number' => $request->input('phone_number'),
            'note' => $request->input('notes'),
        ]);

        $bill = Bill::create([
            'id_customer' => $customer->id,
            'date_order' => now()->format('Y-m-d'),
            'total' => $cart->totalPrice,
            'payment' => $request->input('payment_method'),
            'note' => $request->input('notes'),
        ]);

        foreach ($cart->items as $productId => $item) {
            BillDetail::create([
                'id_bill' => $bill->id,
                'id_product' => $productId,
                'quantity' => $item['qty'],
                'unit_price' => $item['price'] / $item['qty'],
            ]);
        }

        Session::forget('cart');

        return redirect()->back()->with('success', 'Đặt hàng thành công. Cảm ơn bạn đã mua hàng.');
    }
}
