<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewContract;

/**
 * Service Provider chính của ứng dụng.
 * Đăng ký các service và cấu hình khởi động khi ứng dụng chạy.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Đăng ký các service vào container (IoC).
     * Dùng để bind interface với implementation.
     */
    public function register(): void
    {
        //
    }

    /**
     * Khởi động các service sau khi tất cả provider đã được đăng ký.
     * Chia sẻ dữ liệu giỏ hàng cho các view cần thiết.
     */
    public function boot(): void
    {
        // Chia sẻ thông tin giỏ hàng cho layout chính và trang checkout
        // Giúp hiển thị số lượng sản phẩm trên icon giỏ hàng ở header
        View::composer(['layouts.app', 'checkout'], function (ViewContract $view) {
            if (Session::has('cart')) {
                $oldCart = Session::get('cart');
                $cart    = new Cart($oldCart);

                // Truyền các biến giỏ hàng vào view
                $view->with([
                    'cart'         => $cart,
                    'productCarts' => $cart->items,
                    'totalPrice'   => $cart->totalPrice,
                    'totalQty'     => $cart->totalQty,
                ]);
            }
        });
    }
}
