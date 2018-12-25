<?php

declare(strict_types=1);

namespace Auth\Handler;

use Psr\Container\ContainerInterface;

class UserLoginHandlerFactory
{
    public function __invoke(ContainerInterface $container) : UserLoginHandler
    {
        return new UserLoginHandler();
    }
}
