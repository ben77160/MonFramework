<?php
namespace App\Auth;

use Framework\Auth\ForbiddenException;
use Framework\Response\RedirectResponse;
use Framework\Session\FlashService;
use Framework\Session\SessionInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ForbiddenMiddleware implements MiddlewareInterface
{

    /**
     * @var string
     */
    private $loginPath;
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(string $loginPath, SessionInterface $session)
    {
        $this->loginPath = $loginPath;
        $this->session = $session;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        try {
            return $delegate->process($request);
        } catch (ForbiddenException $exception) {
            $this->session->set('auth.redirect', $request->getUri()->getPath());
            (new FlashService($this->session))->error('Vous devez posséder un compte pour accéder à cette page');
            return new RedirectResponse($this->loginPath);
        }
    }
}
