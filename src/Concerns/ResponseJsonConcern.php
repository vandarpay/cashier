<?php

namespace Vandar\Cashier\Concerns;

use Vandar\Cashier\Client\CasingFormatter;

trait ResponseJsonConcern
{
    public function json()
    {
        $response = json_decode($this->getBody(), true);
        return CasingFormatter::convertFailedResponseFormat($response);
    }
}
