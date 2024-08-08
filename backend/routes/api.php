<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MailgunController;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\ValidateMailgunWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route
    ::get('/user', function (Request $request) {
        return $request->user();
    })->middleware(['auth:sanctum']);

Route
    ::group([
        'prefix' => 'customers',
    ], function () {
        Route
            ::get('get/all', [CustomerController::class, 'getAll']);
    });

Route
    ::group([
        'prefix' => 'orders',
    ], function () {
        Route::group([
            'prefix' => 'get',
        ], function () {
            Route
                ::get('all', [OrderController::class, 'getAll']);
            Route
                ::post('customer/{customerUuid}', [OrderController::class, 'getByCustomerUuid']);
            Route
                ::get('{uuid}', [OrderController::class, 'getByUuid']);
        });
    });

Route
    ::group([
        'prefix' => 'mailgun',
    ], function () {
        Route
            ::post('store', [MailgunController::class, 'store'])
            ->middleware(ValidateMailgunWebhook::class);
        Route
            ::post('send-reply', [MailgunController::class, 'sendReply']);
    });
