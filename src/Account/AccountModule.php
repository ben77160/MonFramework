<?php
namespace App\Account;

use App\Account\Action\AccountAction;
use App\Account\Action\AccountEditAction;
use App\Account\Action\SignupAction;
use Framework\Auth\LoggedInMiddleware;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use Framework\Router;

class AccountModule extends Module
{

    const MIGRATIONS = __DIR__ . '/migrations';

    const DEFINITIONS = __DIR__ . '/definitions.php';

    public function __construct(Router $router, RendererInterface $renderer)
    {
        $renderer->addPath('account', __DIR__ . '/views');
        $router->get('/inscription', SignupAction::class, 'account.signup');
        $router->post('/inscription', SignupAction::class);
        $router->get('/mon-profil', [LoggedInMiddleware::class, AccountAction::class], 'account');
        $router->post('/mon-profil', [LoggedInMiddleware::class, AccountEditAction::class]);
    }
}
