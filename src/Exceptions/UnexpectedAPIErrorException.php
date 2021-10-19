<?php

namespace Vandar\Cashier\Exceptions;

use Psr\Http\Message\ResponseInterface;

class UnexpectedAPIErrorException extends ResponseException
{
    public function __construct(ResponseInterface $response, array $context=[])
    {
        parent::__construct($response, 'Unexpected API Error (HTTP ' . $response->getStatusCode(). ') occurred. Contact Vandar support.', 500, $context);
    }
}