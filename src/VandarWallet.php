<?php

namespace Vandar\VandarCashier;

use Vandar\VandarCashier\Controllers\VandarWalletController;

class VandarWallet
{

    public static function balance()
    {
        VandarWalletController::balance($business = null);
    }
    
}
