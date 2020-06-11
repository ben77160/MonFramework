<?php
namespace Framework\Middleware;

use Framework\Renderer\RendererInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RendererRequestMiddleware implements MiddlewareInterface
{

    /**
     * @var RendererInterface
     */
    private $renderer;

    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $domain = sprintf(
            '%s://%s%s',
            $request->getUri()->getScheme(),
            $request->getUri()->getHost(),
            $request->getUri()->getPort() ? ':' . $request->getUri()->getPort() : ''
        );
        $this->renderer->addGlobal('domain', $domain);
        return $delegate->process($request);
    }
}
