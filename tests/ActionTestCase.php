<?php

namespace Tests;

use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class ActionTestCase extends TestCase
{

    protected function makeRequest(string $path = '/', array $params = [])
    {
        $method = empty($params) ? 'GET' : 'POST';
        return (new ServerRequest($method, new Uri($path)))
            ->withParsedBody($params);
    }

    protected function assertRedirect(ResponseInterface $response, string $path)
    {
        $this->assertSame(301, $response->getStatusCode());
        $this->assertEquals([$path], $response->getHeader('Location'));
    }
}
