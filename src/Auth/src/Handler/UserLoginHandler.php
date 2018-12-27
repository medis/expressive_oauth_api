<?php

declare(strict_types=1);

namespace Auth\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Authentication\Session\PhpSession;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Session\SessionInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class UserLoginHandler implements RequestHandlerInterface
{
    private const REDIRECT_ATTRIBUTE = 'authentication:redirect';

    /** @var PhpSession */
    private $adapter;

    /** @var TemplateRendererInterface */
    private $renderer;

    /** @var UrlHelper */
    private $helper;

    public function __construct(TemplateRendererInterface $renderer, PhpSession $adapter, UrlHelper $helper)
    {
        $this->renderer = $renderer;
        $this->adapter = $adapter;
        $this->helper = $helper;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $session = $request->getAttribute('session');
        $redirect = $this->getRedirect($request, $session);

        if ('POST' === $request->getMethod()) {
            return $this->handleLoginAttempt($request, $session, $redirect);
        }

        // Display initial login form
        $session->set(self::REDIRECT_ATTRIBUTE, $redirect);
        return new HtmlResponse($this->renderer->render(
            'app::login',
            []
        ));
    }

    private function getRedirect(ServerRequestInterface $request, SessionInterface $session) : string
    {
        $redirect = $session->get(self::REDIRECT_ATTRIBUTE);

        if (! $redirect) {
            $redirect = $request->getHeaderLine('Referer');
            if (in_array($redirect, ['', $this->helper->generate('login')], true)) {
                $redirect = $this->helper->generate('dashboard.landing');
            }
        }

        return $redirect;
    }

    private function handleLoginAttempt(ServerRequestInterface $request, SessionInterface $session, string $redirect) : ResponseInterface {
        // Login was successful
        if ($this->adapter->authenticate($request)) {
            $session->unset(self::REDIRECT_ATTRIBUTE);
            return new RedirectResponse($redirect);
        }

        // Login failed
        return new HtmlResponse($this->renderer->render(
            'app::login',
            ['error' => 'Invalid credentials; please try again']
        ));
    }
}
