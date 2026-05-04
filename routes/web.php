<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'getIndex'])->name('home');
Route::get('/trangchu', [PageController::class, 'getIndex'])->name('banhang.index');
Route::get('/tim-kiem', [PageController::class, 'search'])->name('banhang.search');

Route::get('foods/manage', [FoodController::class, 'manage'])->name('foods.manage');
Route::resource('foods', FoodController::class);
Route::get('category/{category}', [FoodController::class, 'showByCategory'])->name('foods.category');
Route::get('/chitiet/{id}', [PageController::class, 'getChiTiet'])->name('banhang.chitiet');

Route::get('gio-hang', [PageController::class, 'getCart'])->name('banhang.giohang');
Route::get('add-to-cart/{id}', [PageController::class, 'addToCart'])->name('banhang.addtocart');
Route::get('del-cart/{id}', [PageController::class, 'delCartItem'])->name('banhang.xoagiohang');
Route::post('update-cart/{id}', [PageController::class, 'updateCart'])->name('banhang.updatecart');
Route::get('checkout', [PageController::class, 'getCheckout'])->name('banhang.getdathang');
Route::post('checkout', [PageController::class, 'postCheckout'])->name('banhang.postdathang');

Route::get('/dangky', [PageController::class, 'getSignin'])->name('getsignin');
Route::post('/dangky', [PageController::class, 'postSignin'])->name('postsignin');
Route::get('/dangnhap', [PageController::class, 'getLogin'])->name('getlogin');
Route::post('/dangnhap', [PageController::class, 'postLogin'])->name('postlogin');
Route::get('/dangxuat', [PageController::class, 'getLogout'])->name('getlogout');

Route::get('/admin/dangnhap', [UserController::class, 'getLogin'])->name('admin.getLogin');
Route::post('/admin/dangnhap', [UserController::class, 'postLogin'])->name('admin.postLogin');
Route::get('/admin/dangxuat', [UserController::class, 'getLogout'])->name('admin.getLogout');

Route::prefix('admin')->middleware(['admin'])->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::prefix('category')->group(function () {
        Route::get('danhsach', [CategoryController::class, 'getCateList'])->name('admin.getCateList');
        Route::get('them', [CategoryController::class, 'getCateAdd'])->name('admin.getCateAdd');
        Route::post('them', [CategoryController::class, 'postCateAdd'])->name('admin.postCateAdd');
        Route::get('xoa/{id}', [CategoryController::class, 'getCateDelete'])->name('admin.getCateDelete');
        Route::get('sua/{id}', [CategoryController::class, 'getCateEdit'])->name('admin.getCateEdit');
        Route::post('sua/{id}', [CategoryController::class, 'postCateEdit'])->name('admin.postCateEdit');
    });

    // Quản lý sản phẩm
    Route::prefix('sanpham')->group(function () {
        Route::get('danhsach', [AdminController::class, 'foodList'])->name('admin.food.list');
        Route::get('them', [AdminController::class, 'foodCreate'])->name('admin.food.create');
        Route::post('them', [AdminController::class, 'foodStore'])->name('admin.food.store');
        Route::get('sua/{id}', [AdminController::class, 'foodEdit'])->name('admin.food.edit');
        Route::post('sua/{id}', [AdminController::class, 'foodUpdate'])->name('admin.food.update');
        Route::get('xoa/{id}', [AdminController::class, 'foodDelete'])->name('admin.food.delete');
    });

    // Quản lý đơn hàng
    Route::prefix('donhang')->group(function () {
        Route::get('danhsach', [AdminController::class, 'orderList'])->name('admin.order.list');
        Route::get('chitiet/{id}', [AdminController::class, 'orderDetail'])->name('admin.order.detail');
        Route::post('trangthai/{id}', [AdminController::class, 'orderUpdateStatus'])->name('admin.order.status');
        Route::get('xoa/{id}', [AdminController::class, 'orderDelete'])->name('admin.order.delete');
    });

    // Quản lý người dùng
    Route::prefix('nguoidung')->group(function () {
        Route::get('danhsach', [AdminController::class, 'userList'])->name('admin.user.list');
        Route::get('them', [AdminController::class, 'userCreate'])->name('admin.user.create');
        Route::post('them', [AdminController::class, 'userStore'])->name('admin.user.store');
        Route::get('sua/{id}', [AdminController::class, 'userEdit'])->name('admin.user.edit');
        Route::post('sua/{id}', [AdminController::class, 'userUpdate'])->name('admin.user.update');
        Route::get('xoa/{id}', [AdminController::class, 'userDelete'])->name('admin.user.delete');
    });
});