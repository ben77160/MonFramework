<?php

use App\Basket\Action\BasketAction;
use App\Basket\BasketFactory;
use App\Basket\Twig\BasketTwigExtension;

use function DI\add;
use function DI\get;
use function DI\object;
use function DI\factory;

return [
    'twig.extensions' => add([
        \DI\get(BasketTwigExtension::class)
    ]),
    App\Basket\Basket::class => factory(BasketFactory::class),
    BasketAction::class => object()->constructorParameter('stripeKey', get('stripe.key'))
];
