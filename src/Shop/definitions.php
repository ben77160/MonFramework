<?php
return [
    'admin.widgets' => \DI\add([
        \DI\get(\App\Shop\ShopWidget::class)
    ])
];
