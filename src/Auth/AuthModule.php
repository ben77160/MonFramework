<?php
namespace App\Auth;

use App\Auth\Action\LoginAction;
use App\Auth\Action\LoginAttemptAction;
use App\Auth\Action\LogoutAction;
use App\Auth\Action\LogoutActionAction;
use App\Auth\Action\PasswordForgetAction;
use App\Auth\Action\PasswordResetAction;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Framework\Router\Route;
use Psr\Container\ContainerInterface;

class AuthModule extends Module
{

    const DEFINITIONS = __DIR__ . '/config.php';

    const MIGRATIONS =  __DIR__ . '/db/migrations';

    const SEEDS =  __DIR__ . '/db/seeds';

    public function __construct(ContainerInterface $container, Router $router, RendererInterface $renderer)
    {
        $renderer->addPath('auth', __DIR__ . '/views');
        $router->get($container->get('auth.login'), LoginAction::class, 'auth.login');
        $router->post($container->get('auth.login'), LoginAttemptAction::class);
        $router->post('/logout', LogoutAction::class, 'auth.logout');
        $router->any('/password', PasswordForgetAction::class, 'auth.password');
        $router->any('/password/reset/{id:\d+}/{token}', PasswordResetAction::class, 'auth.reset');
    }
}
