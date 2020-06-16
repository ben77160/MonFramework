<?php


namespace App\Framework\Response;

use Framework\Response\RedirectResponse;
use Psr\Http\Message\ServerRequestInterface;

class RedirectBackResponse extends RedirectResponse
{

    public function __construct(ServerRequestInterface $request)
    {
        parent::__construct($request->getServerParams()['HTTP_REFERER'] ?? '/');
    }
}
