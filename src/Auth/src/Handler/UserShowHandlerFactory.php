<?php

declare(strict_types=1);

namespace Auth\Handler;

use Auth\Service\User\FindUserByUuidInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class UserShowHandlerFactory
{
    public function __invoke(ContainerInterface $container) : UserShowHandler
    {
        return new UserShowHandler(
            $container->get(EntityManagerInterface::class),
            $container->get(FindUserByUuidInterface::class)
        );
    }
}
