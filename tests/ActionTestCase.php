<?php
namespace Tests;

use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;

class ActionTestCase extends TestCase
{

    protected function makeRequest(string $path, array $params = [])
    {
        $method = empty($params) ? 'GET' : 'POST';
        return (new ServerRequest($method, new Uri($path)))
            ->withParsedBody($params);
    }
}
