<?php

declare(strict_types=1);

namespace Dashboard\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Authentication\UserInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class TokensPageHandler implements RequestHandlerInterface
{
    /**
     * @var TemplateRendererInterface
     */
    private $renderer;

    public function __construct(TemplateRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        /** @var \Zend\Expressive\Session\LazySession $session */
        $session = $request->getAttribute('session');
        $user = $session->get(UserInterface::class);

        return new HtmlResponse($this->renderer->render(
            'dashboard::tokens-page',
            [
                'user' => $user
            ]
        ));
    }
}
