<?php

namespace Vandar\Cashier\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Vandar\Cashier\Models\Mandate;
use Vandar\Cashier\Models\Settlement;

class SettlementCreating
{

    use Dispatchable, SerializesModels;

    public $settlement;


    public function __construct(Settlement $settlement)
    {
        $this->settlement = $settlement;
    }
}