<?php

declare(strict_types = 1);

namespace Auth\Service\User;

use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use Auth\Entity\User;

class DoctrineFindUserByUuidFactory
{
    /**
     * @param ContainerInterface $container
     * @return callable
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : callable
    {
        return new DoctrineFindUserByUuid(
            $container->get(EntityManagerInterface::class)->getRepository(User::class)
        );
    }
}