<?php

use Illuminate\Support\Facades\Route;
use Modules\Course\src\Http\Controllers\CourseController;

Route::group(['namespace' => 'Modules\Course\src\Http\Controllers', 'middleware' => 'web'], function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::prefix('courses')->name('courses.')->group(function () {
            Route::get('/', [CourseController::class, 'index'])->name('index');
            Route::get('data', [CourseController::class, 'data'])->name('data');
            Route::get('/create', [CourseController::class, 'create'])->name('create');
            Route::post('/create', [CourseController::class, 'store'])->name('store');
            Route::get('/edit/{course}', [CourseController::class, 'edit'])->name('edit');
            Route::put('/edit/{course}', [CourseController::class, 'update'])->name('update');
            Route::delete('/delete/{course}', [CourseController::class, 'delete'])->name('delete');
        });
    });
});
