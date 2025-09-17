<?php

use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'v1', 'namespace' => '\App\Http\Controllers\Api\V1'], function () {
    Route::post('/register', [\App\Http\Controllers\Api\V1\AuthController::class, 'register']);
});

Route::group(['prefix' => 'v1', 'namespace' => '\App\Http\Controllers\Api\V1'], function () {
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('invoices', InvoiceController::class);

    // Route::post('/invoice/bulk', ['uses' => 'InvoiceController@bulkStore']);
    Route::post('/invoices/bulk', [InvoiceController::class, 'bulkStore']);

    Route::get('/report/{type}', [ReportController::class, 'show']);

    Route::get('/send-message', function () {
        Telegram::sendMessage([
            'chat_id' => '648000103',
            'text' => 'From Larvel 12.x',
        ]);

        return 'Message Send!';
    });

    Route::get('/set-webhook', function () {
        $url = 'https://579ec8a4da7f.ngrok-free.app/api/webhook'; // Your ngrok URL
        $response = Telegram::setWebhook(['url' => $url]);

        return $response;
    });

});

Route::post('/webhook', function () {
    $update = Telegram::getWebhookUpdate();
    $message = $update->getMessage();
    $user = $message->getFrom();
    $text = $message->getText();

    Telegram::sendMessage([
        'chat_id' => $user->getId(),
        'text' => "{$user->getFirstName()} said: $text",
    ]);
});
