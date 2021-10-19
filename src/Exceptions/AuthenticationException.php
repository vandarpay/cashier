<?php

namespace Vandar\Cashier\Exceptions;

use Psr\Http\Message\ResponseInterface;

class AuthenticationException extends ResponseException
{
    public function __construct(ResponseInterface $response, array $context=[])
    {
        parent::__construct($response, 'Failed to authenticate in Vandar APIs, Make sure you have correct environment variables.', 500, $context);
    }
}