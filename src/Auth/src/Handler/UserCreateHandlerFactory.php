<?php

declare(strict_types=1);

namespace Auth\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class UserCreateHandlerFactory
{
    public function __invoke(ContainerInterface $container) : UserCreateHandler
    {
        return new UserCreateHandler(
            $container->get(EntityManagerInterface::class)
        );
    }
}
