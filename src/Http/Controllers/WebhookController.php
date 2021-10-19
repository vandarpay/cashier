<?php

namespace Vandar\Cashier\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Vandar\Cashier\Models\Settlement;
use Vandar\Cashier\Models\Withdrawal;

class WebhookController extends Controller
{
    public function handleWithdrawalNotification(Request $request)
    {
        $withdrawal = Withdrawal::query()->where('withdrawal_id', $request->get('withdrawal_id'))->first();
        if (!$withdrawal) {
            return;
        }
        $withdrawal->update($request->only(['status',]));
    }

    public function handleSettlementNotification(Request $request)
    {
        $settlement = Settlement::query()->where('transaction_id', $request->get('transaction_id'))->first();

        if(! $settlement) {
            return;
        }

        $settlement->update($request->only(['status', 'settlement_date', 'settlement_date_jalali']));
    }
}