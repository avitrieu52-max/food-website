<?php

use App\Http\Controllers\FoodController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FoodController::class, 'index'])->name('home');
Route::get('foods/manage', [FoodController::class, 'manage'])->name('foods.manage');
Route::resource('foods', FoodController::class);
Route::get('category/{category}', [FoodController::class, 'showByCategory'])->name('foods.category');