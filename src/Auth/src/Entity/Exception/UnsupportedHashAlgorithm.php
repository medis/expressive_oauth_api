<?php

namespace Auth\Entity\Exception;

class UnsupportedHashAlgorithm extends \DomainException
{
    public static function fromAlgorithm($algo)
    {
        return new self(sprintf('Unsupported hash algorithm: %s', $algo));
    }
}