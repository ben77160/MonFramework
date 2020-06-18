<?php

namespace App\Basket\Action;

use App\Basket\Basket;
use App\Basket\PurchaseBasket;
use Framework\Actions\RouterAwareAction;
use Framework\Auth;
use Framework\Response\RedirectResponse;
use Framework\Session\FlashService;
use Psr\Http\Message\ServerRequestInterface;

class OrderProcessAction
{

    /**
     * @var PurchaseBasket
     */
    private $purchaseBasket;
    /**
     * @var Auth
     */
    private $auth;

    /**
     * @var FlashService
     */
    private $flashService;
    /**
     * @var Basket
     */
    private $basket;

    use RouterAwareAction;

    public function __construct(
        PurchaseBasket $purchaseBasket,
        Auth $auth,
        FlashService $flashService,
        Basket $basket
    ) {
        $this->purchaseBasket = $purchaseBasket;
        $this->auth = $auth;
        $this->flashService = $flashService;
        $this->basket = $basket;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $params = $request->getParsedBody();
        $stripeToken = $params['stripeToken'];
        $this->purchaseBasket->process($this->basket, $this->auth->getUser(), $stripeToken);
        $this->basket->empty();
        $this->flashService->success('Merci pour votre achat');
        return new RedirectResponse('/');
    }
}
