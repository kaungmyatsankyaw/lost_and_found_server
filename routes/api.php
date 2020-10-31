<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/** User */
Route::group(['prefix' => 'user'], function () {
    /** Login User */
    Route::post('/login', [\App\Http\Controllers\Api\UserController::class, 'login']);

    /** Register User */
    Route::post('/register', [\App\Http\Controllers\Api\UserController::class, 'register']);


});


/** Protected Routes */
Route::group(['middleware' => 'auth:sanctum'], function () {
    /** User */
    Route::group(['prefix' => 'user'], function () {
        /** Get User Detail */
        Route::post('/profile', [\App\Http\Controllers\Api\UserController::class, 'profile']);

        /** Get User Item */
        Route::post('/item',[\App\Http\Controllers\Api\UserController::class,'items']);
    });
    /** Item */
    Route::group(['prefix' => 'item'], function () {
        /** Create */
        Route::post('/create', [\App\Http\Controllers\Api\Item\IndexController::class, 'create']);
        /** List */
        Route::post('/list', [\App\Http\Controllers\Api\Item\IndexController::class, 'getItems']);

        /** Delete */
        Route::post('/delete',[\App\Http\Controllers\Api\Item\IndexController::class,'delete']);
    });
});

/** Item */
Route::group(['prefix' => 'item'], function () {
    /** Create */
    Route::get('/create', [\App\Http\Controllers\Api\Item\IndexController::class, 'create']);
});
