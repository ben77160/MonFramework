<?php
namespace Tests\Framework\Middleware;

use Framework\Middleware\NotFoundMiddleware;
use Framework\Middleware\RouterMiddleware;
use Framework\Router;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class RouterMiddlewareTest extends TestCase
{

    public function makeMiddleware($route)
    {
        $router = $this->getMockBuilder(Router::class)->getMock();
        $router->method('match')->willReturn($route);
        return new RouterMiddleware($router);
    }

    public function testPassParameters()
    {
        $route = new Router\Route('demo', 'trim', ['id' => 2]);
        $middleware = $this->makeMiddleware($route);
        $demo = function ($request) use ($route) {
            $this->assertEquals(2, $request->getAttribute('id'));
            $this->assertEquals($route, $request->getAttribute(get_class($route)));
            return new Response();
        };
        call_user_func_array($middleware, [new ServerRequest('GET', '/demo'), $demo]);
    }

    public function testCallNext()
    {
        $route = new Router\Route('demo', 'trim', ['id' => 2]);
        $middleware = $this->makeMiddleware(null);
        $response = new Response();
        $demo = function ($request) use ($response) {
            return $response;
        };
        $this->assertEquals($response, call_user_func_array($middleware, [new ServerRequest('GET', '/demo'), $demo]));
    }
}
