<?php

namespace App\Basket\Action;

use App\Basket\Basket;
use App\Basket\Table\BasketTable;
use App\Shop\Entity\Product;
use App\Shop\Table\ProductTable;
use Framework\Api\Stripe;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;
use Staaky\VATRates\VATRates;

class OrderRecapAction
{

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var Stripe
     */
    private $stripe;
    /**
     * @var BasketTable
     */
    private $basketTable;
    /**
     * @var Basket
     */
    private $basket;

    public function __construct(
        RendererInterface $renderer,
        BasketTable $basketTable,
        Stripe $stripe,
        Basket $basket
    ) {
        $this->renderer = $renderer;
        $this->stripe = $stripe;
        $this->basketTable = $basketTable;
        $this->basket = $basket;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $params = $request->getParsedBody();
        $stripeToken = $params['stripeToken'];
        $card = $this->stripe->getCardFromToken($stripeToken);
        $vat = (new VATRates())->getStandardRate($card->country);
        $basket = $this->basket;
        $this->basketTable->hydrateBasket($basket);
        $price = floor($basket->getPrice() * (($vat + 100) / 100));
        return $this->renderer->render('@basket/recap', compact(
            'basket',
            'vat',
            'stripeToken',
            'price',
            'card'
        ));
    }
}
