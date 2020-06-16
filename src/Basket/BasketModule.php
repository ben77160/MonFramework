<?php
namespace App\Basket;

use Framework\Module;
use Framework\Renderer\RendererInterface;
use Framework\Router;

class BasketModule extends Module
{

    const DEFINITIONS = __DIR__ . '/definitions.php';

    const NAME = 'basket';

    public function __construct(Router $router, RendererInterface $renderer)
    {
        $router->post('/panier/ajouter/{id:\d+}', Action\BasketAction::class, 'basket.add');
        $router->post('/panier/changer/{id:\d+}', Action\BasketAction::class, 'basket.change');
        $router->get('/panier', Action\BasketAction::class, 'basket');
        $renderer->addPath('basket', __DIR__ . '/views');
    }
}
