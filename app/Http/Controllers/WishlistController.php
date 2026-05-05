<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Wishlist::where('user_id', Auth::id())
            ->with('food')
            ->get();

        return view('wishlist', compact('wishlistItems'));
    }

    public function toggle(Request $request, $foodId)
    {
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json(['redirect' => route('getlogin')]);
            }
            return redirect()->route('getlogin');
        }

        $food = Food::findOrFail($foodId);
        $existing = Wishlist::where('user_id', Auth::id())
            ->where('food_id', $foodId)
            ->first();

        if ($existing) {
            $existing->delete();
            $inWishlist = false;
            $message = 'Đã xóa khỏi danh sách yêu thích.';
        } else {
            Wishlist::create(['user_id' => Auth::id(), 'food_id' => $foodId]);
            $inWishlist = true;
            $message = 'Đã thêm vào danh sách yêu thích!';
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'inWishlist' => $inWishlist, 'message' => $message]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function remove($foodId)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('food_id', $foodId)
            ->delete();

        return redirect()->route('wishlist.index')->with('success', 'Đã xóa khỏi danh sách yêu thích.');
    }
}
