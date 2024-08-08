<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmailController;
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
            ::get('get/all', [CustomerController::class, 'getAll'])
            ->middleware(['auth:sanctum']);
    });

Route
    ::group([
        'prefix' => 'orders',
    ], function () {
        Route::group([
            'prefix' => 'get',
        ], function () {
            Route
                ::get('all', [OrderController::class, 'getAll'])
                ->middleware(['auth:sanctum']);
            Route
                ::post('customer/{customerUuid}', [OrderController::class, 'getByCustomerUuid'])
                ->middleware(['auth:sanctum']);
            Route
                ::get('{uuid}', [OrderController::class, 'getByUuid'])
                ->middleware(['auth:sanctum']);
        });
    });

Route
    ::group([
        'prefix' => 'email',
    ], function () {
        Route
            ::post('store', [EmailController::class, 'store'])
            ->middleware((function () {
                return [match (true) {
                    config('mail.incoming.middleware') === 'mailgun' => ValidateMailgunWebhook::class,
                    // ... condition => middleware
                    default => null,
                }];
            })());
        Route
            ::post('send-reply', [EmailController::class, 'sendReply'])
            ->middleware(['auth:sanctum']);
    });
