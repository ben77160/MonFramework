<?php

namespace Framework\Auth;

use Framework\Auth;

class RoleMiddlewareFactory
{

    /**
     * @var Auth
     */
    private $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function makeForRole($role): RoleMiddleware
    {
        return new RoleMiddleware($this->auth, $role);
    }
}
