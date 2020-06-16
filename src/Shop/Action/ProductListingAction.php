<?php
namespace App\Shop\Action;

use App\Shop\Table\ProductTable;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;

class ProductListingAction
{

    /**
     * @var RendererInterface
     */
    private $renderer;
    /**
     * @var ProductTable
     */
    private $productTable;

    public function __construct(RendererInterface $renderer, ProductTable $productTable)
    {
        $this->renderer = $renderer;
        $this->productTable = $productTable;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $params = $request->getQueryParams();
        $page = $params['p'] ?? 1;
        $products = $this->productTable->findPublic()->paginate(12, $page);
        return $this->renderer->render('@shop/index', compact('products', 'page'));
    }
}
