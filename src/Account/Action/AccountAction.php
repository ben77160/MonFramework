<?php
namespace App\Account\Action;

use Framework\Auth;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ServerRequestInterface;

class AccountAction
{

    /**
     * @var RendererInterface
     */
    private $renderer;
    /**
     * @var Auth
     */
    private $auth;

    public function __construct(
        RendererInterface $renderer,
        Auth $auth
    ) {
    
        $this->renderer = $renderer;
        $this->auth = $auth;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $user = $this->auth->getUser();
        return $this->renderer->render('@account/account', compact('user'));
    }
}
