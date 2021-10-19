<?php

namespace Vandar\Cashier\Exceptions;

use Psr\Http\Message\ResponseInterface;

class InvalidPayloadException extends ResponseException
{
    public function __construct(ResponseInterface $response, array $context=[])
    {
        parent::__construct($response, 'Given payload caused a ' . $response->getStatusCode() . ' error.', 500, $context);
    }
}