<?php
namespace Tests\Framework\Middleware;

use Framework\Middleware\NotFoundMiddleware;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class NotFoundMiddlewareTest extends TestCase
{

    public function testSendNotFound()
    {
        $request = (new ServerRequest('GET', '/demo'));
        $middleware = new NotFoundMiddleware();
        $response = call_user_func_array($middleware, [$request, function () {
        }]);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
