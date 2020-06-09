<?php
namespace Framework\Middleware;

use Framework\Router;
use GuzzleHttp\Psr7\Response;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DispatcherMiddleware implements MiddlewareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $route = $request->getAttribute(Router\Route::class);
        if (is_null($route)) {
            return $delegate->process($request);
        }
        $callback = $route->getCallback();
        if (!is_array($callback)) {
            $callback = [$callback];
        }
        return (new CombinedMiddleware($this->container, $callback))->process($request, $delegate);
    }
}
