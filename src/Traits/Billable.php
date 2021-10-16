<?php

namespace Vandar\Cashier\Traits;

use Vandar\Cashier\Concerns\MandateConcern;
use Vandar\Cashier\Concerns\RelationsConcern;

trait Billable
{
    use MandateConcern;
    use RelationsConcern;
}
