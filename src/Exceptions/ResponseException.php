<?php

namespace Vandar\Cashier\Exceptions;

use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class ResponseException extends RuntimeException
{
    /** @var ResponseInterface */
    protected $response;

    protected $context;

    public function __construct(ResponseInterface $response, $message='', $code=500, array $context=[])
    {
        parent::__construct($message, $code);
        $this->response = $response;
        $this->context = $context;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function context()
    {
        return $this->context;
    }

    public function getUrl()
    {
        return $this->context['url'] ?? null;
    }

    public function getPayload()
    {
        return $this->context['options']['form_params'] ?? null;
    }

    public function getHeaders()
    {
        return $this->context['options']['headers'] ?? null;
    }

    public function errors()
    {
        return $this->response->json() ?? $this->response->getBody();
    }
}