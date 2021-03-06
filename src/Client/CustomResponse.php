<?php

namespace Vandar\Cashier\Client;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Vandar\Cashier\Concerns\ResponseJsonConcern;

/**
 * CustomResponse acts as a proxy over the normal Guzzle response class, adding additional concerns for extra functionality.
 *
 * This should be deprecated once Vandar Cashier's minimum requirements reaches Laravel 7.x
 */
class CustomResponse implements ResponseInterface
{
    use ResponseJsonConcern;

    protected $response;

    /**
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;

//        if ($this->response->getStatusCode() > 299)
//            throw new UnauthorizedException($this->response->getReasonPhrase());
    }

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function getReasonPhrase(): string
    {
        return $this->response->getReasonPhrase();
    }

    public function withStatus($code, $reasonPhrase = ''): ResponseInterface
    {
        return $this->response->withStatus($code, $reasonPhrase);
    }


    public function getProtocolVersion()
    {
        return $this->response->getProtocolVersion();
    }

    public function withProtocolVersion($version)
    {
        return $this->response->withProtocolVersion($version);
    }

    public function getHeaders()
    {
        return $this->response->getHeaders();
    }

    public function hasHeader($name)
    {
        return $this->response->hasHeader($name);
    }

    public function getHeader($name)
    {
        return $this->response->getHeader($name);
    }

    public function getHeaderLine($name)
    {
        return $this->response->getHeaderLine($name);
    }

    public function withHeader($name, $value)
    {
        return $this->response->withHeader($name, $value);
    }

    public function withAddedHeader($name, $value)
    {
        return $this->response->withAddedHeader($name, $value);
    }

    public function withoutHeader($name)
    {
        return $this->response->withoutHeader($name);
    }

    public function getBody()
    {
        return $this->response->getBody();
    }

    public function withBody(StreamInterface $body)
    {
        return $this->response->withBody($body);
    }
}
