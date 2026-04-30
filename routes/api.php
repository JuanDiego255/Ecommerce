<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiLoginController;
use App\Http\Controllers\Api\ClientApiController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['tenancy'])->group(function () {
    // Auth
    Route::post('/login',          [ApiLoginController::class, 'login']);
    Route::post('/auth/register',  [ApiLoginController::class, 'register']);

    // Client routes — require authenticated user
    Route::middleware('auth:sanctum')->group(function () {
        Route::get   ('/client/addresses',     [ClientApiController::class, 'addresses']);
        Route::post  ('/client/addresses',     [ClientApiController::class, 'storeAddress']);
        Route::delete('/client/addresses/{id}',[ClientApiController::class, 'deleteAddress']);

        Route::get   ('/client/orders',        [ClientApiController::class, 'orders']);
        Route::post  ('/client/orders',        [ClientApiController::class, 'storeOrder']);

        Route::put   ('/client/profile',       [ClientApiController::class, 'updateProfile']);
    });
});
