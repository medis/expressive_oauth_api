<?php

declare(strict_types=1);

namespace Auth\Handler;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Authentication\Session\PhpSession;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Template\TemplateRendererInterface;

class UserLoginHandlerFactory
{
    public function __invoke(ContainerInterface $container) : UserLoginHandler
    {
        return new UserLoginHandler(
            $container->get(TemplateRendererInterface::class),
            $container->get(PhpSession::class),
            $container->get(UrlHelper::class)
        );
    }
}
