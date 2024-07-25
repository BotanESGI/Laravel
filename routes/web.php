<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DashboardController;
use \App\Http\Controllers\CartController;
use \App\Http\Controllers\OrderController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\InvoiceController;

Route::get('/', [ProductController::class, 'index'])->name("product.index");
Route::get('/{name}-{id}', [ProductController::class, 'show'])->name('product.show');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/paid', [CartController::class, 'paidCart'])->name('cart.paid');
    Route::get('/cart', [CartController::class, 'getCart'])->name('cart.get');
    Route::delete('/cart/{id}', [CartController::class, 'removeProductCart'])->name('cart.product.delete');
    Route::put('/cart/{cart}', [CartController::class, 'updateProductQuantity'])->name('cart.product.update');
    Route::post('/cart/{id}', [CartController::class, 'AddProductCart'])->name('cart.product.add');
    Route::get('/myOrders', [OrderController::class, 'getOrders'])->name('orders');
    Route::get('/order-details/{order}', [OrderController::class, 'getOrderDetails'])->name('ordersDetails');
});

Route::middleware(['role.admin'])->group(function () {
    Route::prefix("/product")->name('product.')->group(function(){
        Route::get('/create', [ProductController::class, 'create'])->name("create");
        Route::post('/create', [ProductController::class, 'createProduct'])->name("createProduct");
        Route::get("/modify/{id}", [ProductController::class, 'modifyProduct'])->name("modify");
        Route::put("/update/{id}", [ProductController::class, 'updateProduct'])->name("update");
        Route::delete('/delete/{id}', [ProductController::class, 'deleteProduct'])->name('delete');
    });

    Route::prefix("/dashboard")->name("dashboard.")->group(function(){
        Route::get('/', [DashboardController::class, 'index'])->name("index");
        Route::prefix("/user")->name('user.')->group(function(){
            Route::get('/create', [UsersController::class, 'create'])->name("create");
            Route::post('/create', [UsersController::class, 'createUser'])->name("createUser");
            Route::get("/modify/{id}", [UsersController::class, 'modify'])->name("modify");
            Route::put("/update/{id}", [UsersController::class, 'modifyUser'])->name("modifyUser");
            Route::delete('/delete/{id}', [UsersController::class, 'delete'])->name('delete');
        });
        Route::prefix("/order")->name('order.')->group(function(){
            Route::get('/show/{id}', [OrderController::class, 'show'])->name("show");
            Route::put("/update/{id}", [OrderController::class, "update"])->name("update");
        });
    });

    Route::prefix("/order")->name('order.')->group(function(){
        Route::get("/", [OrderController::class, 'index'])->name("index");
    });



});
Route::get('/invoice/{order}', [InvoiceController::class, 'generateInvoice'])->name('invoice.generate');


require __DIR__.'/auth.php';
