<?php

namespace Vandar\VandarCashier\Traits;

use Illuminate\Http\Request;

trait VandarVerify
{
    use \Vandar\VandarCashier\Utilities\Verify;

    public static function vandar_verify()
    {
        self::verify((\Request::query()));
    }
}
