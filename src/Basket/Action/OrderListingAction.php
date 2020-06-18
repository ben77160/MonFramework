<?php

namespace App\Basket\Action;

use App\Basket\Table\OrderTable;
use Framework\Auth;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;

class OrderListingAction
{

    /**
     * @var RendererInterface
     */
    private $renderer;
    /**
     * @var OrderTable
     */
    private $orderTable;
    /**
     * @var Auth
     */
    private $auth;

    public function __construct(
        RendererInterface $renderer,
        OrderTable $orderTable,
        Auth $auth
    ) {
        $this->renderer = $renderer;
        $this->orderTable = $orderTable;
        $this->auth = $auth;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $page = $request->getQueryParams()['p'] ?? 1;
        $orders = $this->orderTable->findForUser($this->auth->getUser())->paginate(10, $page);
        $this->orderTable->findRows($orders);
        return $this->renderer->render('@basket/orders', compact('orders', 'page'));
    }
}
