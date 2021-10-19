<?php

namespace Vandar\Cashier\Exceptions;

use Psr\Http\Message\ResponseInterface;

class TooManyRequestsException extends ResponseException
{
    public function __construct(ResponseInterface $response, array $context)
    {
        parent::__construct($response, 'Encountered ' . $response->getStatusCode() . ' error while sending a request to Vandar.', 500, $context);
    }
}