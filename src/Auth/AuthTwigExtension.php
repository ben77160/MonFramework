<?php
namespace App\Auth;

use Framework\Auth;
use Twig_Extension;

class AuthTwigExtension extends \Twig_Extension
{

    /**
     * @var Auth
     */
    private $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('current_user', [$this->auth, 'getUser'])
        ];
    }
}
