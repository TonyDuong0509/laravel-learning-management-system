<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Modules\User\src\Http\Controllers'], function () {
    Route::prefix('users')->group(function () {
        Route::get('/', 'UserController@index');
    });
});
