<?php

namespace Vandar\Cashier\Exceptions;

use Psr\Http\Message\ResponseInterface;
use Vandar\Cashier\Vandar;

class DeprecatedAPIException extends ResponseException
{
    public function __construct(ResponseInterface $response, array $context=[])
    {
        $context['cashier_version'] = Vandar::VERSION;
        parent::__construct($response, 'Received an error code of ' . $response->getStatusCode() . ' while sending a request to Vandar. Update your package or contact support.', 500, $context);
    }
}