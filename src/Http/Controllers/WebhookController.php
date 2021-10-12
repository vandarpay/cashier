<?php

namespace Vandar\Cashier\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Vandar\Cashier\Models\Withdrawal;

class WebhookController extends Controller
{
    public function handleWithdrawalNotification(Request $request)
    {
        $withdrawal = Withdrawal::query()->where('withdrawal_id', $request->get('withdrawal_id'))->first();
        if(! $withdrawal){
            return;
        }
        $withdrawal->update($request->only(['status', ]));
    }
}