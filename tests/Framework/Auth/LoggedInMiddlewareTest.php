<?php
namespace Tests\Framework\Auth;

use Framework\Auth;
use Framework\Middleware\TrailingSlashMiddleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Interop\Http\ServerMiddleware\DelegateInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class LoggedInMiddlewareTest extends TestCase
{

    public function makeMiddleware($user)
    {
        $auth = $this->getMockBuilder(Auth::class)->getMock();
        $auth->method('getUser')->willReturn($user);
        return new Auth\LoggedInMiddleware($auth);
    }

    public function makeDelegate($calls)
    {
        $delegate = $this->getMockBuilder(DelegateInterface::class)->getMock();
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $delegate->expects($calls)->method('process')->willReturn($response);
        return $delegate;
    }

    public function testThrowIfNoUser()
    {
        $request = (new ServerRequest('GET', '/demo/'));
        $this->expectException(Auth\ForbiddenException::class);
        $this->makeMiddleware(null)->process(
            $request,
            $this->makeDelegate($this->never())
        );
    }

    public function testNextIfUser()
    {
        $user = $this->getMockBuilder(Auth\User::class)->getMock();
        $request = (new ServerRequest('GET', '/demo/'));
        $this->makeMiddleware($user)->process(
            $request,
            $this->makeDelegate($this->once())
        );
    }
}
