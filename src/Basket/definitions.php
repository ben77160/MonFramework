<?php
return [
    'twig.extensions' => \DI\add([
        \DI\get(\App\Basket\Twig\BasketTwigExtension::class)
    ]),
    App\Basket\Basket::class => \DI\object(\App\Basket\SessionBasket::class)
];
