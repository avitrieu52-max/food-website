<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller quản lý danh sách sản phẩm yêu thích (Wishlist).
 * Yêu cầu người dùng đã đăng nhập để sử dụng.
 */
class WishlistController extends Controller
{
    /**
     * Hiển thị trang danh sách sản phẩm yêu thích của người dùng đang đăng nhập.
     */
    public function index()
    {
        // Lấy tất cả sản phẩm yêu thích kèm thông tin sản phẩm
        $wishlistItems = Wishlist::where('user_id', Auth::id())
            ->with('food')
            ->get();

        return view('wishlist', compact('wishlistItems'));
    }

    /**
     * Thêm hoặc xóa sản phẩm khỏi danh sách yêu thích (toggle).
     * Hỗ trợ cả AJAX và redirect thông thường.
     * Nếu chưa đăng nhập thì chuyển hướng về trang đăng nhập.
     */
    public function toggle(Request $request, $foodId)
    {
        // Kiểm tra đăng nhập
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json(['redirect' => route('getlogin')]);
            }
            return redirect()->route('getlogin');
        }

        $food = Food::findOrFail($foodId);

        // Kiểm tra sản phẩm đã có trong wishlist chưa
        $existing = Wishlist::where('user_id', Auth::id())
            ->where('food_id', $foodId)
            ->first();

        if ($existing) {
            // Đã có → xóa khỏi wishlist
            $existing->delete();
            $inWishlist = false;
            $message    = 'Đã xóa khỏi danh sách yêu thích.';
        } else {
            // Chưa có → thêm vào wishlist
            Wishlist::create(['user_id' => Auth::id(), 'food_id' => $foodId]);
            $inWishlist = true;
            $message    = 'Đã thêm vào danh sách yêu thích!';
        }

        // Trả về JSON nếu là AJAX request
        if ($request->ajax()) {
            return response()->json(['success' => true, 'inWishlist' => $inWishlist, 'message' => $message]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Xóa một sản phẩm cụ thể khỏi danh sách yêu thích.
     */
    public function remove($foodId)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('food_id', $foodId)
            ->delete();

        return redirect()->route('wishlist.index')->with('success', 'Đã xóa khỏi danh sách yêu thích.');
    }
}
