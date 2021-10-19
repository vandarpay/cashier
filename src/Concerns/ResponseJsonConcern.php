<?php

namespace Vandar\Cashier\Concerns;

use Vandar\Cashier\Client\CasingFormatter;

trait ResponseJsonConcern
{
    public function json($format_response = true)
    {
        $response = json_decode($this->getBody(), true);

//        if ($format_response && $this->getStatusCode() > 299 && is_array($response)) {
//            return CasingFormatter::convertFailedResponseFormat($response);
//        }

        return $response;
    }
}
