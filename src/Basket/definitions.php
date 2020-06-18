<?php
return [
    'twig.extensions' => \DI\add([
        \DI\get(\App\Basket\Twig\BasketTwigExtension::class)
    ]),
    App\Basket\Basket::class => \DI\factory(\App\Basket\BasketFactory::class)
];
