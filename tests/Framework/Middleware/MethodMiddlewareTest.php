<?php
namespace Tests\Framework\Middleware;

use Framework\Middleware\MethodMiddleware;
use GuzzleHttp\Psr7\ServerRequest;
use Interop\Http\ServerMiddleware\DelegateInterface;
use PHPUnit\Framework\TestCase;

class MethodMiddlewareTest extends TestCase {

    /**
     * @var MethodMiddleware
     */
    private $middleware;

    public function setUp()
    {
        $this->middleware = new MethodMiddleware();
    }

    public function testAddMethod () {
        $delegate = $this->getMockBuilder(DelegateInterface::class)
            ->setMethods(['process'])
            ->getMock();

        $delegate->expects($this->once())
            ->method('process')
            ->with($this->callback(function ($request) {
                return $request->getMethod() === 'DELETE';
            }));

        $request = (new ServerRequest('POST', '/demo'))
            ->withParsedBody(['_method' => 'DELETE']);
        $this->middleware->process($request, $delegate);
    }

}