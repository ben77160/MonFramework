<?php


namespace App\Blog\Actions;

use App\Blog\Table\PostTable;
use App\Framework\Actions\RouterAwareAction;
use App\Framework\Session\FlashService;
use App\Framework\Session\SessionInterface;
use App\Framework\Validator;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use function DI\string;

class AdminBlogAction
{
    /**
     * @var RendererInterface $renderer
     */
    private $renderer;

    /**
     * @var Router
     */
    private $router;
    /**
     * @var PostTable
     */
    private $postTable;

    /**
     * @var $flash
     */
    private $flash;
    use RouterAwareAction;

    public function __construct(RendererInterface $renderer, Router $router, PostTable $postTable, FlashService $flash)
    {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->postTable = $postTable;
        $this->flash = $flash;
    }

    public function __invoke(Request $request)
    {
        if ($request->getMethod() === 'DELETE') {
            return $this->delete($request);
        }

        if (substr((string)$request->getUri(), -3) === 'new') {
            return $this->create($request);
        }
        if ($request->getAttribute('id')) {
            return $this->edit($request);
        }
        return $this->index($request);
    }

    public function index(Request $request): string
    {
        $params = $request->getQueryParams();
        $items= $this->postTable->findPaginated(12, $params['p'] ?? 1);

        return $this->renderer->render('@blog/admin/index', compact('items'));
    }

    /**
     * Edit un article
     * @param Request $request
     * @return ResponseInterface|string
     */
    public function edit(Request $request)
    {
        $item = $this->postTable->find($request->getAttribute('id'));

        if ($request->getMethod() === 'POST') {
            $params = $this->getParams($request);
            $params['updated_at'] = date('Y-m-d H:i:s');
            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->postTable->insert($params);
                $this->flash->success('L\'article a bien été modifié');
                return $this->redirect('blog.admin.index');
            }
            $errors = $validator->getErrors();
            $params['id'] = $item->id;
            $item = $params;
        }

        /**
         * On retourne la vue(render) puis on passe sur notre enregistrement sur le paramêtre compact
         */
        return $this->renderer->render('@blog/admin/edit', compact('item', 'errors'));
    }

    /**
     * Crée un nouvel article
     * @param Request $request
     * @return ResponseInterface|string
     */
    public function create(Request $request)
    {
        if ($request->getMethod() === 'POST') {
            $params = $this->getParams($request);
            $params = array_merge($params, [
               'updated_at' => date('Y-m-d H:i:s'),
               'created_at' => date('Y-m-d H:i:s')
            ]);
            $validator = $this->getValidator($request);
            if ($validator->isValid()) {
                $this->postTable->insert($params);
                $this->flash->success('L\'article a bien été modifié');
                return $this->redirect('blog.admin.index');
            }
            $errors = $validator->getErrors();
            $item = $params;
        }
        //On retourne la vue(render) puis on passe sur notre enregistrement sur le paramêtre compact
        return $this->renderer->render('@blog/admin/create', compact('item', 'errors'));
    }

    public function delete(Request $request)
    {
        $this->postTable->delete($request->getAttribute('id'));
        return $this->redirect('blog.admin.index');
    }


    private function getParams(Request $request)
    {
        return  array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, ['name', 'slug', 'content']);
        }, ARRAY_FILTER_USE_KEY);
    }

    private function getValidator(Request $request)
    {
        return (new Validator($request->getParsedBody()))
           ->required('content', 'name', 'slug')
           ->length('content', 10)
           ->length('name', 2, 250)
           ->length('slug', 2, 50)
           ->slug('slug');
    }
}
