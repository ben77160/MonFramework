<?php
namespace App\Blog;

use App\Admin\AdminModule;
use App\Blog\Actions\PostCrudAction;
use App\Blog\Actions\BlogAction;
use App\Framework\Module;
use DI\Container;
use Framework\Renderer\RendererInterface;
use Framework\Router;

class BlogModule extends Module
{
    const DEFINITIONS = __DIR__ . '/config.php';

    const MIGRATIONS =  __DIR__ . '/db/migrations';

    const SEEDS =  __DIR__ . '/db/seeds';

    public function __construct(Container $container)
    {
        $container->get(RendererInterface::class)->addPath('blog', __DIR__ . '/views');
        /** @var Router $router */
        $router = $container->get(Router::class);
        $router->get($container->get('blog.prefix'), BlogAction::class, 'blog.index');
        $router->get($container->get('blog.prefix') .
            '/{slug:[a-z\-0-9]+}-{id:[0-9]+}', BlogAction::class, 'blog.show');

        if ($container->has('admin.prefix')) {
            $prefix = $container->get('admin.prefix');
            $router->crud("$prefix/posts", PostCrudAction::class, 'blog.admin');
        }
    }
}
