<?php

declare(strict_types=1);

namespace Auth\Handler;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper\UrlHelper;

class UserLogoutHandlerFactory
{
    public function __invoke(ContainerInterface $container) : UserLogoutHandler
    {
        return new UserLogoutHandler($container->get(UrlHelper::class));
    }
}
