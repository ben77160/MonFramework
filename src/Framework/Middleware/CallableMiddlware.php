<?php

namespace Framework\Middleware;

use GuzzleHttp\Psr7\Response;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CallableMiddlware implements MiddlewareInterface, \Psr\Http\Server\MiddlewareInterface
{

    private $callable;

    public function __construct($callable)
    {
        $this->callable = $callable;
    }

    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new Response();
    }
}
