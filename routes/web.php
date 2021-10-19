<?php

use Illuminate\Support\Facades\Route;

Route::name('webhook.')->group(function () {
    Route::post('webhook/withdrawal', 'WebhookController@handleWithdrawalNotification')->name('withdrawal');
    Route::post('webhook/settlement', 'WebhookController@handleSettlementNotification')->name('settlement');
});