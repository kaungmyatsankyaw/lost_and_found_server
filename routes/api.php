<?php

use App\Http\Controllers\Api\Item\IndexController;
use App\Http\Controllers\Api\NotiController;
use App\Http\Controllers\Api\TokenController;
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
        Route::post('/item', [\App\Http\Controllers\Api\UserController::class, 'items']);
    });
    /** Item */
    Route::group(['prefix' => 'item'], function () {

        /** Detail */
        Route::post('/detail', [IndexController::class, 'detail']);

        /** Create */
        Route::post('/create', [\App\Http\Controllers\Api\Item\IndexController::class, 'create']);
        /** List */
        Route::post('/list', [\App\Http\Controllers\Api\Item\IndexController::class, 'getItems']);

        /** Delete */
        Route::post('/delete', [\App\Http\Controllers\Api\Item\IndexController::class, 'delete']);

        /** Get location */
        Route::post('/location', [\App\Http\Controllers\Api\Item\IndexController::class, 'getLocation']);

        /** Update Items */
        Route::post('/update', [\App\Http\Controllers\Api\Item\IndexController::class, 'editItem']);
    });
});



/** Noti Token */
Route::group(['prefix' => 'noti'], function () {
    /** Store Token */
    Route::post('/store/token', [\App\Http\Controllers\Api\TokenController::class, 'storeToken']);

    /** Update Noti Status */
    Route::post('/update/status', [\App\Http\Controllers\Api\TokenController::class, 'updateToken']);
});


Route::post('/noti/test', [\App\Http\Controllers\Api\NotiController::class, 'test']);
