<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController; // <-- Tambahkan ini
use App\Http\Controllers\PaymentCallbackController; // <-- Tambahkan ini untuk webhook

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama / Menu untuk semua pengunjung
Route::get('/', [HomeController::class, 'index'])->name('home');

// Grup rute untuk user yang sudah terotentikasi
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    // Rute untuk profil user
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rute untuk menampilkan halaman riwayat pesanan user
    Route::get('/my-orders', [UserOrderController::class, 'index'])->name('order.index');
      
    // =================================================================================
    // == BARU: Rute ini adalah "jembatan" yang hilang untuk membayar pesanan tertunda ==
    // =================================================================================
    Route::post('/orders/{order}/pay-again', [PaymentController::class, 'payAgain'])->name('order.pay_again');

    // === ROUTE KERANJANG BELANJA ===
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{product_id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
});

// Grup rute khusus untuk Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // ... (rute admin Anda tidak perlu diubah)
    Route::get('/dashboard', fn() => redirect()->route('admin.products.index'))->name('dashboard');
    Route::resource('products', AdminProductController::class);
    Route::resource('categories', AdminCategoryController::class);
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::patch('orders/{order}/accept', [AdminOrderController::class, 'accept'])->name('orders.accept');
    Route::post('orders/{order}/reject', [AdminOrderController::class, 'reject'])->name('orders.reject');
    Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
    Route::delete('orders/destroy-all', [AdminOrderController::class, 'destroyAll'])->name('orders.destroyAll');
});

// =======================================================================
// == KRUSIAL: Rute untuk menerima notifikasi (webhook) dari Midtrans ==
// =======================================================================
Route::post('/payment/callback', [PaymentCallbackController::class, 'receive'])->name('payment.callback');


// Memuat rute autentikasi bawaan
require __DIR__.'/auth.php';