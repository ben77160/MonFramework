<?php
namespace App\Auth\Action;

use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;

class LoginAction
{

    /**
     * @var RendererInterface
     */
    private $renderer;

    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        return $this->renderer->render('@auth/login');
    }
}
