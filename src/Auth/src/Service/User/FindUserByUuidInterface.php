<?php

declare(strict_types = 1);

namespace Auth\Service\User;

use Auth\Entity\User;
use Ramsey\Uuid\UuidInterface;

interface FindUserByUuidInterface
{
    /**
     * @param UuidInterface $slug
     * @return User
     * @throws Exception\UserNotFound
     */
    public function __invoke(UuidInterface $slug): User;
}