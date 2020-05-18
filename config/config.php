<?php

use App\Framework\Session\PHPSession;
use App\Framework\Session\SessionInterface;
use App\Framework\Twig\FlashExtension;
use App\Framework\Twig\PagerFantaExtension;
use App\Framework\Twig\TextExtension;
use App\Framework\Twig\TimeExtension;
use Framework\Renderer\RendererInterface;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router;
use Psr\Container\ContainerInterface;
use function DI\factory;
use function DI\object;

return [
    'database.host' => 'localhost',
    'database.username' => 'root',
    'database.password' => '',
    'database.name' => 'monsupersite',
    'views.path' => dirname(__DIR__) . '/views',
    'twig.extensions' => [
        \DI\get(\Framework\Router\RouterTwigExtension::class),
        \DI\get(PagerFantaExtension::class),
        \DI\get(TextExtension::class),
        \DI\get(TimeExtension::class),
        \DI\get(FlashExtension::class)
    ],
    SessionInterface::class => \DI\object(PHPSession::class),
    Router::class => object(),
    RendererInterface::class => factory(TwigRendererFactory::class),
    \PDO::class => function (ContainerInterface $c) {
        return new PDO(
            'mysql:host=' .$c->get('database.host') . ';dbname=' . $c->get('database.name'),
            $c->get('database.username'),
            $c->get('database.password'),
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }
];
