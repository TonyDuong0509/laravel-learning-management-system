<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\src\Http\Controllers\CategoryController;

Route::group(['namespace' => 'Modules\Category\src\Http\Controllers', 'middleware' => 'web'], function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('data', [CategoryController::class, 'data'])->name('data');
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::post('/create', [CategoryController::class, 'store'])->name('store');
            Route::get('/edit/{category}', [CategoryController::class, 'edit'])->name('edit');
            Route::put('/edit/{category}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/delete/{category}', [CategoryController::class, 'delete'])->name('delete');
        });
    });
});
