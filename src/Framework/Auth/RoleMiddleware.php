<?php
namespace Framework\Auth;

use Framework\Auth;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RoleMiddleware implements MiddlewareInterface
{

    /**
     * @var Auth
     */
    private $auth;
    /**
     * @var string
     */
    private $role;

    public function __construct(Auth $auth, string $role)
    {
        $this->auth = $auth;
        $this->role = $role;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $user = $this->auth->getUser();
        if ($user === null || !in_array($this->role, $user->getRoles())) {
            throw new ForbiddenException();
        }
        return $delegate->process($request);
    }
}
