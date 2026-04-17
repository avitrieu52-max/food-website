<?php

use App\Http\Controllers\FoodController;
use App\Http\Controllers\PageController;
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