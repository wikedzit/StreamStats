<?php

use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\StreamStatsController;
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

Route::controller(SocialAuthController::class)->group(function(){
    Route::get("auth/callback", "callback");
    Route::post("auth/logout", "logout");
});

Route::controller(StreamStatsController::class)->group(function () {
    Route::group(['prefix' => 'stats'], function () {
        Route::get('/', 'getStats');
    });
});
