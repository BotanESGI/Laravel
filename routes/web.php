<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use \App\Http\Controllers\CartController;
use App\Http\Middleware\AdminMiddleware;

Route::get('/', [ProductController::class, 'index'])->name("product.index");


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/paid', [CartController::class, 'paidCart'])->name('cart.paid');
    Route::get('/cart', [CartController::class, 'getCart'])->name('cart.get');
    Route::delete('/cart/{id}', [CartController::class, 'removeProductCart'])->name('cart.product.delete');
    Route::put('/cart/{cart}', [CartController::class, 'updateProductQuantity'])->name('cart.product.update');
    Route::post('/cart/{id}', [CartController::class, 'AddProductCart'])->name('cart.product.add');
    Route::get('/transaction', [CartController::class, 'GetTransaction'])->name('transaction.history');
});

Route::middleware(['role.admin'])->group(function () {
        Route::prefix("/product")->group(function(){
            Route::get('/create', [ProductController::class, 'create'])->name("product.create");
            Route::post('/create', [ProductController::class, 'createProduct'])->name("createProduct");
            Route::get("/modify/{id}", [ProductController::class, 'modifyProduct'])->name("product.modify");
            Route::put("/update/{id}", [ProductController::class, 'updateProduct'])->name("product.update");
            Route::delete('/delete/{id}', [ProductController::class, 'deleteProduct'])->name('product.delete');
    });

});


require __DIR__.'/auth.php';
