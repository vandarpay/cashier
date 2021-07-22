<?php

namespace Vandar\VandarCashier;

use Illuminate\Http\Request;
use Vandar\VandarCashier\Controllers\VandarIPGController;
use Illuminate\Support\Facades\Http;

class VandarIPG
{

    /**
     * Send payment parameters to get Payment Token
     * 
     * @param array $params
     * @param array $morphs
     */
    public  static function pay($params, $morphs)
    {
        return VandarIPGController::pay($params, $morphs);
    }
    
}
