<?php

use App\Admin\AdminModule;
use App\Blog\BlogModule;
use DI\ContainerBuilder;
use GuzzleHttp\Psr7\ServerRequest;
use function Http\Response\send;

require dirname(__DIR__) .'/vendor/autoload.php';

$modules = [
    AdminModule::class,
    BlogModule::class
];

$builder = new ContainerBuilder();
$builder->addDefinitions(dirname(__DIR__). '/config/config.php');
foreach ($modules as $module) {
    if ($module::DEFINITIONS) {
        $builder->addDefinitions($module::DEFINITIONS);
    }
}
$builder->addDefinitions(dirname(__DIR__). '/config.php');
$container = $builder->build();

$app = new \Framework\App($container, $modules);

if (php_sapi_name() !== "cli") {
    $response = $app->run(ServerRequest::fromGlobals());
    send($response);
}