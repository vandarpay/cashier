<?php

namespace Vandar\Cashier\Events;


class MandateRedirect
{
    use \Vandar\Cashier\Events\MandateCreating;

    public function handle(MandateCreating $mandate)
    {
    }
}
