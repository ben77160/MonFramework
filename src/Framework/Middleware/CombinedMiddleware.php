<?php
namespace Framework\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CombinedMiddleware implements MiddlewareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var array
     */
    private $middlewares;

    public function __construct(ContainerInterface $container, array $middlewares)
    {
        $this->container = $container;
        $this->middlewares = $middlewares;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $delegate = new CombinedMiddlewareDelegate($this->container, $this->middlewares, $delegate);
        return $delegate->process($request);
    }
}
