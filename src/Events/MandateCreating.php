<?php

namespace Vandar\Cashier\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Vandar\Cashier\Models\Mandate;

class MandateCreating{

    use Dispatchable, SerializesModels;

    public $mandate;

    
    public function __construct(Mandate $mandate)
    {
        $this->mandate = $mandate;
    }
}