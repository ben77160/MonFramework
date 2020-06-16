<?php


namespace App\Basket\Action;

use App\Basket\Basket;
use App\Basket\Table\BasketTable;
use App\Framework\Response\RedirectBackResponse;
use App\Shop\Table\ProductTable;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;

class BasketAction
{

    /**
     * @var Basket
     */
    private $basket;

    /**
     * @var RendererInterface
     */
    private $renderer;
    /**
     * @var BasketTable
     */
    private $basketTable;

    public function __construct(
        Basket $basket,
        RendererInterface $renderer,
        BasketTable $basketTable
    ) {
        $this->basket = $basket;
        $this->renderer = $renderer;
        $this->basketTable = $basketTable;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        if ($request->getMethod() === 'GET') {
            return $this->show();
        } else {
            if ($request->getMethod() === 'POST') {
                $product = $this->basketTable->getProductTable()->find((int)$request->getAttribute('id'));
                $params = $request->getParsedBody();
                $this->basket->addProduct($product, $params['quantity'] ?? null);
                return new RedirectBackResponse($request);
            }
        }
    }

    private function show()
    {
        $this->basketTable->hydrateBasket($this->basket);
        return $this->renderer->render('@basket/show', [
            'basket' => $this->basket
        ]);
    }
}
