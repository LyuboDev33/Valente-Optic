<?php

use App\Http\Controllers\Admin\AdminCategoriesController;
use App\Http\Controllers\Admin\AdminOrdersController;
use App\Http\Controllers\Admin\AdminProductsController;
use App\Http\Controllers\Admin\ProductAttributesController;
use Illuminate\Support\Facades\Route;



Route::prefix('admin')
    ->middleware(['auth'])
    ->group(function () {


        Route::prefix('products')->group(function () {
            Route::get('/', [AdminProductsController::class, 'index'])->name('admin.products.index');
            Route::get('/create-product-view', [AdminProductsController::class, 'createProductView'])->name('admin.products.create');
            Route::get('/{slug}', [AdminProductsController::class, 'show'])->name('admin.products.show');
            Route::post('/create', [AdminProductsController::class, 'create'])->name('admin.product.create');

            Route::put('/{product}', [AdminProductsController::class, 'update'])->name('admin.product.update');
            Route::delete('/{product:slug}', [AdminProductsController::class, 'destroy'])->name('admin.products.destroy');
        });



        Route::prefix('product-attributes')->group(function () {
            Route::get('/', [ProductAttributesController::class, 'index'])->name('admin.attributes.index');
            Route::post('/types',          [ProductAttributesController::class, 'storeType'])->name('admin.attributes.types.store');
            Route::delete('/types/{type}', [ProductAttributesController::class, 'destroyType'])->name('admin.attributes.types.destroy');
            Route::post('/values',           [ProductAttributesController::class, 'storeValue'])->name('admin.attributes.values.store');
            Route::delete('/values/{value}', [ProductAttributesController::class, 'destroyValue'])->name('admin.attributes.values.destroy');
        });


        Route::prefix('categories')->group(function () {
            Route::get('/', [AdminCategoriesController::class, 'index'])->name('admin.category.index');
            Route::post('/categories', [AdminCategoriesController::class, 'create'])->name('category.create');
        });

        Route::prefix('orders')->group(function () {
            Route::get('/', [AdminOrdersController::class, 'index'])->name('admin.orders.index');
        });
    });
