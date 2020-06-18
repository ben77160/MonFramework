<?php
namespace App\Basket\Action;

use App\Basket\Entity\Order;
use App\Basket\Table\OrderTable;
use Framework\Auth;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;

class OrderInvoiceAction
{

    /**
     * @var RendererInterface
     */
    private $renderer;
    /**
     * @var Auth
     */
    private $auth;
    /**
     * @var OrderTable
     */
    private $orderTable;

    public function __construct(RendererInterface $renderer, OrderTable $orderTable, Auth $auth)
    {
        $this->renderer = $renderer;
        $this->auth = $auth;
        $this->orderTable = $orderTable;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        /** @var Order $order */
        $order = $this->orderTable->find($request->getAttribute('id'));
        $this->orderTable->findRows([$order]);
        $user = $this->auth->getUser();
        if ($user->getId() !== $order->getUserId()) {
            throw new Auth\ForbiddenException('Vous ne pouvez pas télécharger cette facture');
        }
        return $this->renderer->render('@basket/invoice', compact('order', 'user'));
    }
}
