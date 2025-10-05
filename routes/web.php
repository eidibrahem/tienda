<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;


Route::get('/', [GalleryController::class, 'index'])->name('home');
Route::get('/request/{template}', [OrderController::class, 'create'])->name('orders.create');
Route::post('/request/{template}', [OrderController::class, 'store'])->name('orders.store');

Route::get('/orders/{order}/pay', [PaymentController::class, 'checkout'])->name('orders.pay');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');


Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
Route::middleware('auth')->get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__.'/auth.php';

/**Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    // باقي صفحات اللوحة
}); */