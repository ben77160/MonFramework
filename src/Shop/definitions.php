<?php

use App\Shop\Action\ProductShowAction;

use App\Shop\ShopWidget;
use function DI\get;
use function DI\add;
use function DI\object;
use Framework\Api\Stripe;

return [
    'admin.widgets' => add([
        get(ShopWidget::class)
    ]),
    ProductShowAction::class => object()->constructorParameter('stripeKey', get('stripe.key')),
    Stripe::class => object()->constructor(get('stripe.secret'))
];
