<?php
namespace Tests\Framework\Middleware;

use Framework\Middleware\DispatcherMiddleware;
use Framework\Router\Route;
use GuzzleHttp\Psr7\ServerRequest;
use Interop\Http\ServerMiddleware\DelegateInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

class DispatcherMiddlewareTest extends TestCase
{

    public function testDispatchTheCallback()
    {
        $callback = function () {
            return 'Hello';
        };
        $route = new Route('demo', $callback, []);
        $request = (new ServerRequest('GET', '/demo'))->withAttribute(Route::class, $route);
        $container = $this->getMockBuilder(ContainerInterface::class)->getMock();
        $dispatcher = new DispatcherMiddleware($container);
        $response = call_user_func_array($dispatcher, [$request, function () {
        }]);
        $this->assertEquals('Hello', (string)$response->getBody());
    }

    public function testCallNextIfNotRoutes()
    {
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $delegate = $this->getMockBuilder(DelegateInterface::class)->getMock();
        $container = $this->getMockBuilder(ContainerInterface::class)->getMock();

        $delegate->expects($this->once())->method('process')->willReturn($response);

        $request = (new ServerRequest('GET', '/demo'));
        $dispatcher = new DispatcherMiddleware($container);
        $this->assertEquals($response, call_user_func_array($dispatcher, [$request, [$delegate, 'process']]));
    }
}
