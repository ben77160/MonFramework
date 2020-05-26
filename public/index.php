<?php

use App\Admin\AdminModule;
use App\Blog\BlogModule;

use Framework\Middleware\CsrfMiddleware;
use Framework\Middleware\DispatcherMiddleware;
use Framework\Middleware\MethodMiddleware;
use Framework\Middleware\NotFoundMiddleware;
use Framework\Middleware\RouterMiddleware;
use Framework\Middleware\TrailingSlashMiddleware;
use Middlewares\Whoops;

require dirname(__DIR__) . '/vendor/autoload.php';

$modules = [
    AdminModule::class,
    BlogModule::class
];


$app = (new \Framework\App(dirname(__DIR__) . '/config/config.php'))
      ->addModule(AdminModule::class)
      ->addModule(BlogModule::class)
      ->pipe(Whoops::class)
      ->pipe(TrailingSlashMiddleware::class)
      ->pipe(MethodMiddleware::class)
       ->pipe(CsrfMiddleware::class)
       ->pipe(RouterMiddleware::class)
       ->pipe(DispatcherMiddleware::class)
      ->pipe(NotFoundMiddleware::class);

if (php_sapi_name() !== "cli") {
    $response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
    \Http\Response\send($response);
}
