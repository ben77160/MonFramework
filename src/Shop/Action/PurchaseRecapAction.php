<?php

namespace App\Shop\Action;

use App\Shop\Entity\Product;
use App\Shop\Table\ProductTable;
use Framework\Api\Stripe;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;
use Staaky\VATRates\VATRates;

class PurchaseRecapAction
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
     * @var Stripe
     */
    private $stripe;

    public function __construct(
        RendererInterface $renderer,
        ProductTable $productTable,
        Stripe $stripe
    ) {
        $this->renderer = $renderer;
        $this->productTable = $productTable;
        $this->stripe = $stripe;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $params = $request->getParsedBody();
        $stripeToken = $params['stripeToken'];
        $card = $this->stripe->getCardFromToken($stripeToken);
        $vat = (new VATRates())->getStandardRate($card->country);
        /** @var Product $product */
        $product = $this->productTable->find((int)$request->getAttribute('id'));
        $price = floor($product->getPrice() * (($vat + 100) / 100));
        return $this->renderer->render('@shop/recap', compact(
            'product',
            'vat',
            'stripeToken',
            'price',
            'card'
        ));
    }
}
