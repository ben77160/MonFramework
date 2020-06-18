<?php

namespace App\Basket;

use Framework\Auth\LoggedInMiddleware;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Grafikart\EventManager;

class BasketModule extends Module
{

    const DEFINITIONS = __DIR__ . '/definitions.php';

    const MIGRATIONS = __DIR__ . '/migrations';

    const NAME = 'basket';

    public function __construct(
        Router $router,
        RendererInterface $renderer,
        EventManager $eventManager,
        BasketMerger $basketMerger
    ) {
        $router->post('/panier/ajouter/{id:\d+}', Action\BasketAction::class, 'basket.add');
        $router->post('/panier/changer/{id:\d+}', Action\BasketAction::class, 'basket.change');
        $router->get('/panier', Action\BasketAction::class, 'basket');

        // Tunnel d'achat
        $router->post(
            '/panier/recap',
            [LoggedInMiddleware::class, Action\OrderRecapAction::class],
            'basket.order.recap'
        );
        $router->post(
            '/panier/commander',
            [LoggedInMiddleware::class, Action\OrderProcessAction::class],
            'basket.order.process'
        );

        // Gestion des commandes
        $router->get('/mes-commandes', [LoggedInMiddleware::class, Action\OrderListingAction::class], 'basket.orders');
        $router->get(
            '/mes-commandes/{id:\d+}',
            [LoggedInMiddleware::class, Action\OrderInvoiceAction::class],
            'basket.order.invoice'
        );

        $renderer->addPath('basket', __DIR__ . '/views');
        $eventManager->attach('auth.login', $basketMerger);
    }
}
