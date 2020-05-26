<?php
namespace Framework\Middleware;

use Psr\Http\Message\ServerRequestInterface;

class NotFoundMiddleware
{

    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        var_dump($request);
        die();
        return new Response(404, [], 'Erreur 404');
    }
}
