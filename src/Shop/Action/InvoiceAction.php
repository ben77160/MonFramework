<?php
namespace App\Shop\Action;

use App\Shop\Table\PurchaseTable;
use Framework\Auth;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;

class InvoiceAction
{

    /**
     * @var RendererInterface
     */
    private $renderer;
    /**
     * @var PurchaseTable
     */
    private $purchaseTable;
    /**
     * @var Auth
     */
    private $auth;

    public function __construct(RendererInterface $renderer, PurchaseTable $purchaseTable, Auth $auth)
    {
        $this->renderer = $renderer;
        $this->purchaseTable = $purchaseTable;
        $this->auth = $auth;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $purchase = $this->purchaseTable->findWithProduct($request->getAttribute('id'));
        $user = $this->auth->getUser();
        if ($user->getId() !== $purchase->getUserId()) {
            throw new Auth\ForbiddenException('Vous ne pouvez pas télécharger cette facture');
        }
        return $this->renderer->render('@shop/invoice', compact('purchase', 'user'));
    }
}
