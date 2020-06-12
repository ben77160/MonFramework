<?php
namespace App\Shop;

use App\Shop\Action\AdminProductAction;
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
        $router->crud($container->get('admin.prefix') . '/products', AdminProductAction::class, "shop.admin.products");
    }
}
