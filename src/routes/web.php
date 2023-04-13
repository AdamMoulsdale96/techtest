<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\Catalogue\Product::class, 'index']);
Route::get('/cart', [App\Http\Controllers\Catalogue\Product::class, 'viewCart'])->name('cart.view');
Route::get('/cart/add/{id}', [App\Http\Controllers\Catalogue\Product::class, 'addToCart'])->name('cart.add');
Route::get('/cart/remove/{id}', [App\Http\Controllers\Catalogue\Product::class, 'removeFromCart'])->name('cart.remove');
Route::get('/purchase', [App\Http\Controllers\Sales\Order::class, 'placeOrder'])->name('order.details')->middleware('auth');
Route::post('purchase/complete', [App\Http\Controllers\Sales\Order::class, 'saveOrder'])->name('order.save');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
