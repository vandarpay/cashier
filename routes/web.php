<?php

use Illuminate\Support\Facades\Route;

Route::name('webhook.')->group(function(){
    Route::post('webhook/withdrawal', 'WebhookController@handleWithdrawalNotification')->name('withdrawal');
});