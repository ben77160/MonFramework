<?php
namespace Framework\Middleware;

use GuzzleHttp\Psr7\Response;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CombinedMiddlewareDelegate implements DelegateInterface
{

    /**
     * @var string[]
     */
    private $middlewares = [];

    /**
     * @var int
     */
    private $index = 0;
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var DelegateInterface
     */
    private $delegate;

    public function __construct(ContainerInterface $container, array $middlewares, DelegateInterface $delegate)
    {
        $this->middlewares = $middlewares;
        $this->container = $container;
        $this->delegate = $delegate;
    }

    public function process(ServerRequestInterface $request): ResponseInterface
    {
        $middleware = $this->getMiddleware();
        if (is_null($middleware)) {
            return $this->delegate->process($request);
        } elseif (is_callable($middleware)) {
            $response = call_user_func_array($middleware, [$request, [$this, 'process']]);
            if (is_string($response)) {
                return new Response(200, [], $response);
            }
            return $response;
        } elseif ($middleware instanceof MiddlewareInterface) {
            return $middleware->process($request, $this);
        }
    }

    /**
     * @return object
     */
    private function getMiddleware()
    {
        if (array_key_exists($this->index, $this->middlewares)) {
            if (is_string($this->middlewares[$this->index])) {
                $middleware = $this->container->get($this->middlewares[$this->index]);
            } else {
                $middleware = $this->middlewares[$this->index];
            }
            $this->index++;
            return $middleware;
        }
        return null;
    }
}
