<?php

use App\Account\AccountModule;
use App\Admin\AdminModule;
use App\Auth\AuthModule;
use App\Blog\BlogModule;
use App\Contact\ContactModule;
use App\Shop\ShopModule;
use Framework\Auth\RoleMiddlewareFactory;
use Framework\Middleware\CsrfMiddleware;
use Framework\Middleware\DispatcherMiddleware;
use Framework\Middleware\MethodMiddleware;
use Framework\Middleware\RendererRequestMiddleware;
use Framework\Middleware\RouterMiddleware;
use Framework\Middleware\TrailingSlashMiddleware;
use Framework\Middleware\NotFoundMiddleware;
use GuzzleHttp\Psr7\ServerRequest;
use Middlewares\Whoops;

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

$app = (new \Framework\App(['config/config.php', 'config.php']))
    ->addModule(AdminModule::class)
    ->addModule(ContactModule::class)
    ->addModule(ShopModule::class)
    ->addModule(BlogModule::class)
    ->addModule(AuthModule::class)
    ->addModule(AccountModule::class)
    ->addModule(\App\Basket\BasketModule::class);

$container = $app->getContainer();
$container->get(\Framework\Router::class)->get('/', \App\Blog\Actions\PostIndexAction::class, 'home');
$app->pipe(Whoops::class)
    ->pipe(TrailingSlashMiddleware::class)
    ->pipe(\App\Auth\ForbiddenMiddleware::class)
    ->pipe(
        $container->get('admin.prefix'),
        $container->get(RoleMiddlewareFactory::class)->makeForRole('admin')
    )
    ->pipe(MethodMiddleware::class)
    ->pipe(RendererRequestMiddleware::class)
    ->pipe(CsrfMiddleware::class)
    ->pipe(RouterMiddleware::class)
    ->pipe(DispatcherMiddleware::class)
    ->pipe(NotFoundMiddleware::class);

if (php_sapi_name() !== "cli") {
    $response = $app->run(ServerRequest::fromGlobals());
    \Http\Response\send($response);
}
