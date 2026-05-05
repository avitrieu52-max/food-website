<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ShippingFeeController;
use App\Http\Controllers\SlideController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// ===================== FRONTEND =====================
Route::get('/', [PageController::class, 'getIndex'])->name('home');
Route::get('/trangchu', [PageController::class, 'getIndex'])->name('banhang.index');
Route::get('/tim-kiem', [PageController::class, 'search'])->name('banhang.search');

Route::get('foods/manage', [FoodController::class, 'manage'])->name('foods.manage');
Route::resource('foods', FoodController::class);
Route::get('category/{categoryId}', [FoodController::class, 'showByCategory'])->name('foods.category');
Route::get('/chitiet/{id}', [PageController::class, 'getChiTiet'])->name('banhang.chitiet');

// Giỏ hàng
Route::get('gio-hang', [PageController::class, 'getCart'])->name('banhang.giohang');
Route::post('add-to-cart/{id}', [PageController::class, 'addToCart'])->name('banhang.addtocart');
Route::get('del-cart/{id}', [PageController::class, 'delCartItem'])->name('banhang.xoagiohang');
Route::post('update-cart/{id}', [PageController::class, 'updateCart'])->name('banhang.updatecart');
Route::get('checkout', [PageController::class, 'getCheckout'])->name('banhang.getdathang');
Route::post('checkout', [PageController::class, 'postCheckout'])->name('banhang.postdathang');

// Liên hệ
Route::post('/lien-he', [ContactController::class, 'store'])->name('contact.store');

// Wishlist
Route::get('/yeu-thich', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/yeu-thich/toggle/{foodId}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::get('/yeu-thich/xoa/{foodId}', [WishlistController::class, 'remove'])->name('wishlist.remove');

// Coupon
Route::post('/coupon/apply', [CouponController::class, 'apply'])->name('coupon.apply');
Route::post('/coupon/remove', [CouponController::class, 'remove'])->name('coupon.remove');

// Tài khoản khách hàng
Route::prefix('tai-khoan')->middleware('auth')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('account.index');
    Route::get('/don-hang/{id}', [AccountController::class, 'orderDetail'])->name('account.order.detail');
    Route::post('/cap-nhat', [AccountController::class, 'updateProfile'])->name('account.update');
    Route::post('/doi-mat-khau', [AccountController::class, 'changePassword'])->name('account.password');
});

// Auth khách hàng
Route::get('/dangky', [PageController::class, 'getSignin'])->name('getsignin');
Route::post('/dangky', [PageController::class, 'postSignin'])->name('postsignin');
Route::get('/dangnhap', [PageController::class, 'getLogin'])->name('getlogin');
Route::post('/dangnhap', [PageController::class, 'postLogin'])->name('postlogin');
Route::get('/dangxuat', [PageController::class, 'getLogout'])->name('getlogout');

// ===================== ADMIN AUTH =====================
// Redirect /admin/dangnhap về trang đăng nhập chung
Route::get('/admin/dangnhap', fn() => redirect()->route('getlogin'))->name('admin.getLogin');
Route::post('/admin/dangnhap', [UserController::class, 'postLogin'])->name('admin.postLogin');
Route::get('/admin/dangxuat', [UserController::class, 'getLogout'])->name('admin.getLogout');

// ===================== ADMIN PANEL =====================
Route::prefix('admin')->middleware(['admin'])->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Loại sản phẩm
    Route::prefix('category')->group(function () {
        Route::get('danhsach', [CategoryController::class, 'getCateList'])->name('admin.getCateList');
        Route::get('them', [CategoryController::class, 'getCateAdd'])->name('admin.getCateAdd');
        Route::post('them', [CategoryController::class, 'postCateAdd'])->name('admin.postCateAdd');
        Route::get('xoa/{id}', [CategoryController::class, 'getCateDelete'])->name('admin.getCateDelete');
        Route::get('sua/{id}', [CategoryController::class, 'getCateEdit'])->name('admin.getCateEdit');
        Route::post('sua/{id}', [CategoryController::class, 'postCateEdit'])->name('admin.postCateEdit');
    });

    // Sản phẩm
    Route::prefix('sanpham')->group(function () {
        Route::get('danhsach', [AdminController::class, 'foodList'])->name('admin.food.list');
        Route::get('them', [AdminController::class, 'foodCreate'])->name('admin.food.create');
        Route::post('them', [AdminController::class, 'foodStore'])->name('admin.food.store');
        Route::get('sua/{id}', [AdminController::class, 'foodEdit'])->name('admin.food.edit');
        Route::post('sua/{id}', [AdminController::class, 'foodUpdate'])->name('admin.food.update');
        Route::get('xoa/{id}', [AdminController::class, 'foodDelete'])->name('admin.food.delete');
    });

    // Đơn hàng
    Route::prefix('donhang')->group(function () {
        Route::get('danhsach', [AdminController::class, 'orderList'])->name('admin.order.list');
        Route::get('chitiet/{id}', [AdminController::class, 'orderDetail'])->name('admin.order.detail');
        Route::post('trangthai/{id}', [AdminController::class, 'orderUpdateStatus'])->name('admin.order.status');
        Route::get('xoa/{id}', [AdminController::class, 'orderDelete'])->name('admin.order.delete');
    });

    // Người dùng
    Route::prefix('nguoidung')->group(function () {
        Route::get('danhsach', [AdminController::class, 'userList'])->name('admin.user.list');
        Route::get('them', [AdminController::class, 'userCreate'])->name('admin.user.create');
        Route::post('them', [AdminController::class, 'userStore'])->name('admin.user.store');
        Route::get('sua/{id}', [AdminController::class, 'userEdit'])->name('admin.user.edit');
        Route::post('sua/{id}', [AdminController::class, 'userUpdate'])->name('admin.user.update');
        Route::get('xoa/{id}', [AdminController::class, 'userDelete'])->name('admin.user.delete');
    });

    // Slide/Banner
    Route::prefix('slide')->group(function () {
        Route::get('danhsach', [SlideController::class, 'index'])->name('admin.slide.list');
        Route::get('them', [SlideController::class, 'create'])->name('admin.slide.create');
        Route::post('them', [SlideController::class, 'store'])->name('admin.slide.store');
        Route::get('sua/{id}', [SlideController::class, 'edit'])->name('admin.slide.edit');
        Route::post('sua/{id}', [SlideController::class, 'update'])->name('admin.slide.update');
        Route::get('xoa/{id}', [SlideController::class, 'destroy'])->name('admin.slide.delete');
    });

    // Liên hệ
    Route::prefix('lienhe')->group(function () {
        Route::get('danhsach', [ContactController::class, 'adminIndex'])->name('admin.contact.list');
        Route::get('phanhoi/{id}', [ContactController::class, 'adminReplyForm'])->name('admin.contact.reply');
        Route::post('phanhoi/{id}', [ContactController::class, 'adminReply'])->name('admin.contact.reply.send');
    });

    // Mã giảm giá
    Route::prefix('magiamgia')->group(function () {
        Route::get('danhsach', [CouponController::class, 'adminIndex'])->name('admin.coupon.list');
        Route::get('them', [CouponController::class, 'adminCreate'])->name('admin.coupon.create');
        Route::post('them', [CouponController::class, 'adminStore'])->name('admin.coupon.store');
        Route::get('sua/{id}', [CouponController::class, 'adminEdit'])->name('admin.coupon.edit');
        Route::post('sua/{id}', [CouponController::class, 'adminUpdate'])->name('admin.coupon.update');
        Route::get('xoa/{id}', [CouponController::class, 'adminDelete'])->name('admin.coupon.delete');
    });

    // Phí vận chuyển
    Route::prefix('phivanhuyen')->group(function () {
        Route::get('danhsach', [ShippingFeeController::class, 'adminIndex'])->name('admin.shipping.list');
        Route::get('them', [ShippingFeeController::class, 'adminCreate'])->name('admin.shipping.create');
        Route::post('them', [ShippingFeeController::class, 'adminStore'])->name('admin.shipping.store');
        Route::get('sua/{id}', [ShippingFeeController::class, 'adminEdit'])->name('admin.shipping.edit');
        Route::post('sua/{id}', [ShippingFeeController::class, 'adminUpdate'])->name('admin.shipping.update');
        Route::get('xoa/{id}', [ShippingFeeController::class, 'adminDelete'])->name('admin.shipping.delete');
    });
});
