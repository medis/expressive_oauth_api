<?php

declare(strict_types=1);

namespace Auth\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Authentication\UserInterface;
use Zend\Expressive\Helper\UrlHelper;

class UserLogoutHandler implements RequestHandlerInterface
{
    /** @var UrlHelper */
    private $helper;

    /**
     * UserLogoutHandler constructor.
     * @param UrlHelper $helper
     */
    public function __construct(UrlHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        /** @var \Zend\Expressive\Session\LazySession $session */
        $session = $request->getAttribute('session');
        if ($session->has(UserInterface::class)) {
            $session->clear();
        }

        return new RedirectResponse($this->helper->generate('login'));
    }
}
