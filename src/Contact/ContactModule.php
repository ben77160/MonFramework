<?php
namespace App\Contact;

use Framework\Module;
use Framework\Renderer\RendererInterface;
use Framework\Router;

class ContactModule extends Module
{

    const DEFINITIONS = __DIR__ . '/definitions.php';

    public function __construct(Router $router, RendererInterface $renderer)
    {
        $renderer->addPath('contact', __DIR__);
        $router->get('/contact', ContactAction::class, 'contact');
        $router->post('/contact', ContactAction::class);
    }
}
