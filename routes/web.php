<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini Anda bisa mendaftarkan semua route untuk aplikasi Anda.
|
*/

// == HALAMAN UNTUK SEMUA PENGUNJUNG ==
Route::get('/', [HomeController::class, 'index'])->name('home');


// == GRUP ROUTE UNTUK PENGGUNA TEROTENTIKASI ==
Route::middleware('auth')->group(function () {
    
    // Dashboard & Profil Pengguna
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Riwayat Pesanan Pengguna
    Route::get('/my-orders', [UserOrderController::class, 'index'])->name('order.index');
      
    // Keranjang Belanja
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{product_id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
});


// == GRUP ROUTE KHUSUS UNTUK ADMIN ==
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Redirect dashboard admin ke halaman produk
    Route::get('/dashboard', fn() => redirect()->route('admin.products.index'))->name('dashboard');
    
    // Kelola Produk & Kategori (CRUD)
    Route::resource('products', AdminProductController::class);
    Route::resource('categories', AdminCategoryController::class);
    
    // Kelola Pesanan
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::delete('orders/destroy-all', [AdminOrderController::class, 'destroyAll'])->name('orders.destroyAll');
    Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
    
    // Aksi untuk mengubah status pesanan
    Route::patch('orders/{order}/confirm-payment', [AdminOrderController::class, 'confirmPayment'])->name('orders.confirmPayment');
    Route::post('orders/{order}/process', [AdminOrderController::class, 'process'])->name('orders.process');
    Route::post('orders/{order}/complete', [AdminOrderController::class, 'complete'])->name('orders.complete');
    Route::post('orders/{order}/reject', [AdminOrderController::class, 'reject'])->name('orders.reject');
});


// == ROUTE UNTUK LAYANAN EKSTERNAL (MIDTRANS) ==
Route::post('/payment/callback', [PaymentCallbackController::class, 'receive'])->name('payment.callback');


// Memuat route autentikasi bawaan
require __DIR__.'/auth.php';