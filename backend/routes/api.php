<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\ValidateMailgunMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// api/user/
Route::get('/user', static function (Request $request) {
        return $request->user();
    })
    ->middleware(['auth:sanctum'])
;

// api/customers/
Route::group(['prefix' => 'customers',], static function () {
    // api/customers/get/all
    Route::get('get/all', [CustomerController::class, 'getAll'])
        ->middleware(['auth:sanctum']);
});

// api/orders
Route::group(['prefix' => 'orders',], static function () {
        // api/orders/get/
        Route::group(['prefix' => 'get'], static function () {
            // api/orders/get/all
            Route::get('all', [OrderController::class, 'getAll'])
                ->middleware(['auth:sanctum']);

            // api/orders/get/customer/{customerUuid}
            Route::post('customer/{customerUuid}', [OrderController::class, 'getByCustomerUuid'])
                ->middleware(['auth:sanctum']);

            // api/orders/get/{uuid}
            Route::get('{uuid}', [OrderController::class, 'getByUuid'])
                ->middleware(['auth:sanctum']);
        });
    });

// api/email
Route::group(['prefix' => 'email',], static function () {
        // api/email/store
        Route::post('store', [EmailController::class, 'store'])
            ->middleware(
                ValidateMailgunMiddleware::class
                // other custom middlewares
            );

        // api/email/send-reply
        Route::post('send-reply', [EmailController::class, 'sendReply'])
            ->middleware(['auth:sanctum']);
    });
