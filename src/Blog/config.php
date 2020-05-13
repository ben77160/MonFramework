<?php
use App\Blog\BlogModule;
use function \Di\object;
use function \Di\get;

return [
    'blog.prefix' => '/blog',
    BlogModule::class => object()->constructorParameter('prefix', get('blog.prefix')),
];
