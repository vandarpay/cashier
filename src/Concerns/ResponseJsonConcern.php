<?php

namespace Vandar\Cashier\Concerns;

trait ResponseJsonConcern
{
    public function json()
    {
        return json_decode($this->getBody(), true);
    }
}