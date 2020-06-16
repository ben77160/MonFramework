<?php
namespace App\Shop;

use App\Shop\Action\AdminProductAction;
use App\Shop\Action\ProductDownloadAction;
use App\Shop\Action\ProductListingAction;
use App\Shop\Action\ProductShowAction;
use App\Shop\Action\PurchaseProcessAction;
use App\Shop\Action\PurchaseRecapAction;
use Framework\Auth\LoggedInMiddleware;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Container\ContainerInterface;

class ShopModule extends Module
{

    const MIGRATIONS = __DIR__ . '/db/migrations';
    const SEEDS = __DIR__ . '/db/seeds';
    const DEFINITIONS = __DIR__ . '/definitions.php';

    public function __construct(ContainerInterface $container)
    {
        $container->get(RendererInterface::class)->addPath('shop', __DIR__ . '/views');
        $router = $container->get(Router::class);
        $router->get('/boutique', ProductListingAction::class, 'shop');
        $router->get(
            '/boutique/{id}/download',
            [LoggedInMiddleware::class, ProductDownloadAction::class],
            'shop.download'
        );
        $router->post(
            '/boutique/{id}/recap',
            [LoggedInMiddleware::class, PurchaseRecapAction::class],
            'shop.purchase'
        );
        $router->post(
            '/boutique/{id}/process',
            [LoggedInMiddleware::class, PurchaseProcessAction::class],
            'shop.process'
        );
        $router->get('/boutique/{slug}', ProductShowAction::class, 'shop.show');
        $router->crud($container->get('admin.prefix') . '/products', AdminProductAction::class, "shop.admin.products");
    }
}
