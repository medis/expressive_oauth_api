<?php

declare(strict_types=1);

namespace Auth\Handler;

use Auth\Service\User\FindUserByUuidInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class UserPatchHandlerFactory
{
    public function __invoke(ContainerInterface $container) : UserPatchHandler
    {
        return new UserPatchHandler(
            $container->get(EntityManagerInterface::class),
            $container->get(FindUserByUuidInterface::class)
        );
    }
}
