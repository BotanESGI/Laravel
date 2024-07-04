<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\CartController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/cart', [CartController::class, 'getCart'])->name('cart.get');
    Route::post('/paid', [CartController::class, 'paidCart'])->name('cart.paid');
    Route::delete('/cart/{id}', [CartController::class, 'removeProductCart'])->name('cart.product.delete');
    Route::put('/cart/{cart}', [CartController::class, 'updateProductQuantity'])->name('cart.product.update');

});

require __DIR__.'/auth.php';
