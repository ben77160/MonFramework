<?php
namespace App\Shop\Action;

use App\Shop\Table\ProductTable;
use App\Shop\Table\PurchaseTable;
use Framework\Auth;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;

class ProductShowAction
{

    /**
     * @var RendererInterface
     */
    private $renderer;
    /**
     * @var ProductTable
     */
    private $productTable;
    /**
     * @var string
     */
    private $stripeKey;
    /**
     * @var PurchaseTable
     */
    private $purchaseTable;
    /**
     * @var Auth
     */
    private $auth;

    public function __construct(
        RendererInterface $renderer,
        ProductTable $productTable,
        PurchaseTable $purchaseTable,
        Auth $auth,
        string $stripeKey
    ) {
    
        $this->renderer = $renderer;
        $this->productTable = $productTable;
        $this->stripeKey = $stripeKey;
        $this->purchaseTable = $purchaseTable;
        $this->auth = $auth;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $product = $this->productTable->findBy('slug', $request->getAttribute('slug'));
        $stripeKey = $this->stripeKey;
        $download = false;
        $user = $this->auth->getUser();
        if ($user !== null && $this->purchaseTable->findFor($product, $user)) {
            $download = true;
        }
        return $this->renderer->render('@shop/show', compact('product', 'stripeKey', 'download'));
    }
}
