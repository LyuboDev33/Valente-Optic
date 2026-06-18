<?php

use App\Http\Controllers\FrontEndController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontEndController::class, 'welcome']);

Route::get('/contact', [FrontEndController::class, 'contact'])->name('contact');
Route::get('/about', [FrontEndController::class, 'about'])->name('about');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/category/{category_slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/shop/product/{slug}', [ShopController::class, 'show'])->name('shop.show');

Route::get('/services', [FrontEndController::class, 'services'])->name('services');
Route::get('/service/{service}', [FrontEndController::class, 'serviceShow'])->name('service.show');


Route::get('/dashboard', function () {
    return view('Backend.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
