<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::prefix("/produit")->group(function(){
    Route::get('/', [ProductController::class, 'index'])->name("product.index");
    Route::get('/create', [ProductController::class, 'create'])->name("product.create");
    Route::post('/createproduct', [ProductController::class, 'createProduct'])->name("createProduct");
    Route::get("/modify/{id}", [ProductController::class, 'modifyProduct'])->name("product.modify");
    Route::delete("/delete/{id}", [ProductController::class, 'deleteProduct'])->name("product.delete");
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
