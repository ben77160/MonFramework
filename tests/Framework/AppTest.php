<?php
namespace Tests\Framework;

use Framework\App;
use Framework\Middleware\RouterMiddleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\ServerRequest;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AppTest extends TestCase
{

    /**
     * @var App
     */
    private $app;

    public function setUp()
    {
        $this->app = new App();
    }

    public function testApp()
    {
        $this->app->addModule(get_class($this));
        $this->assertEquals([get_class($this)], $this->app->getModules());
    }

    public function testAppWithArrayDefinition()
    {
        $app = new App(['a' => 2]);
        $this->assertEquals(2, $app->getContainer()->get('a'));
    }

    public function testPipe()
    {
        $middleware = $this->getMockBuilder(MiddlewareInterface::class)->getMock();
        $middleware2 = $this->getMockBuilder(MiddlewareInterface::class)->getMock();
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $middleware->expects($this->once())->method('process')->willReturn($response);
        $middleware2->expects($this->never())->method('process')->willReturn($response);
        $this->assertEquals($response, $this->app->pipe($middleware)->run($request));
    }

    public function testPipeWithClosure()
    {
        $middleware = $this->getMockBuilder(MiddlewareInterface::class)->getMock();
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $middleware->expects($this->once())->method('process')->willReturn($response);
        $this->app
            ->pipe(function ($request, $next) {
                return $next($request);
            })
            ->pipe($middleware);
        $this->assertEquals($response, $this->app->run($request));
    }

    public function testPipeWithoutMiddleware()
    {
        $this->expectException(\Exception::class);
        $this->app->run($this->getMockBuilder(ServerRequestInterface::class)->getMock());
    }

    public function testPipeWithPrefix()
    {
        $middleware = $this->getMockBuilder(MiddlewareInterface::class)->getMock();
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $middleware->expects($this->once())->method('process')->willReturn($response);

        $this->app->pipe('/demo', $middleware);
        $this->assertEquals($response, $this->app->run(new ServerRequest('GET', '/demo/hello')));
        $this->expectException(\Exception::class);
        $this->assertEquals($response, $this->app->run(new ServerRequest('GET', '/hello')));
    }
}
