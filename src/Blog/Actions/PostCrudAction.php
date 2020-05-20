<?php


namespace App\Blog\Actions;

use App\Blog\Table\PostTable;
use App\Framework\Actions\CrudAction;
use App\Framework\Session\FlashService;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ServerRequestInterface;

class PostCrudAction extends CrudAction
{
    protected $viewPath = "@blog/admin/posts";

    protected $routePrefix = "blog.admin";

    public function __construct(RendererInterface $renderer, Router $router, PostTable $table, FlashService $flash)
    {
        parent::__construct($renderer, $router, $table, $flash);
    }

    protected function getParams(ServerRequestInterface $request): array
    {
        $params = array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, ['name', 'slug', 'content', 'created_at']);
        }, ARRAY_FILTER_USE_KEY);
        return array_merge($params, [
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    protected function getValidator(ServerRequestInterface $request)
    {
        //on récupère le validator parent
        return parent::getValidator($request)
           ->required('content', 'name', 'slug', 'created_at')
           ->length('content', 10)
           ->length('name', 2, 250)
           ->length('slug', 2, 50)
            ->datetime('created_at')
           ->slug('slug');
    }
}
