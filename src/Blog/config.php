<?php

use function \Di\get;

return [
    'blog.prefix' => '/blog',
    'admin.widgets' => \DI\add([
        get(\App\Blog\BlogWidget::class)
    ])
];
