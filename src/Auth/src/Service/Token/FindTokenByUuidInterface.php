<?php

declare(strict_types = 1);

namespace Auth\Service\Token;

use Auth\Entity\AccessToken;
use Ramsey\Uuid\UuidInterface;

interface FindTokenByUuidInterface
{
    /**
     * @param UuidInterface $slug
     * @return AccessToken
     * @throws Exception\UserNotFound
     */
    public function __invoke(UuidInterface $slug): AccessToken;
}