<?php
namespace Framework\Router;

use DI\Container;
use Framework\Router;

class RouterFactory
{
    public function __invoke(Container $container)
    {
        $cache = null;
        if ($container->get('env') === 'production') {
            $cache = 'tmp/routes';
        }
        return new Router($cache);
    }
}
