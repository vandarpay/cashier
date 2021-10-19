<?php

namespace Vandar\Cashier\Exceptions;

use Psr\Http\Message\ResponseInterface;

class ExternalAPIException extends ResponseException
{
    protected $context;

    public function __construct(ResponseInterface $response, array $context=[])
    {
        parent::__construct($response, 'Received a response code of ' . $this->response->getStatusCode() . ' while sending a request to Vandar.', 502, $context);
    }
}