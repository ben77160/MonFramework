<?php
use App\Blog\BlogModule;
use App\Blog\DemoExtension;
use function \Di\{object, get};

return [
    'blog.prefix' => '/blog',
    BlogModule::class => object()->constructorParameter('prefix', get('blog.prefix')),
];