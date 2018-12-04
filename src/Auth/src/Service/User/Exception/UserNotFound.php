<?php

declare(strict_types = 1);

namespace Auth\Service\User\Exception;

use Ramsey\Uuid\UuidInterface;

class UserNotFound extends \RuntimeException
{
    public static function fromUuid(UuidInterface $uuid) : self
    {
        return new self(sprintf('User with UUID %s is not found', (string)$uuid));
    }
}