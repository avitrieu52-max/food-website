<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'getIndex'])->name('home');
Route::get('/trangchu', [PageController::class, 'getIndex'])->name('banhang.index');

Route::get('foods/manage', [FoodController::class, 'manage'])->name('foods.manage');
Route::resource('foods', FoodController::class);
Route::get('category/{category}', [FoodController::class, 'showByCategory'])->name('foods.category');
Route::get('/chitiet/{id}', [PageController::class, 'getChiTiet'])->name('banhang.chitiet');

Route::get('add-to-cart/{id}', [PageController::class, 'addToCart'])->name('banhang.addtocart');
Route::get('del-cart/{id}', [PageController::class, 'delCartItem'])->name('banhang.xoagiohang');
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

Route::prefix('admin')->middleware([App\Http\Middleware\AdminLoginMiddleware::class])->group(function () {
    Route::prefix('category')->group(function () {
        Route::get('danhsach', [CategoryController::class, 'getCateList'])->name('admin.getCateList');
        Route::get('them', [CategoryController::class, 'getCateAdd'])->name('admin.getCateAdd');
        Route::post('them', [CategoryController::class, 'postCateAdd'])->name('admin.postCateAdd');
        Route::get('xoa/{id}', [CategoryController::class, 'getCateDelete'])->name('admin.getCateDelete');
        Route::get('sua/{id}', [CategoryController::class, 'getCateEdit'])->name('admin.getCateEdit');
        Route::post('sua/{id}', [CategoryController::class, 'postCateEdit'])->name('admin.postCateEdit');
    });
});