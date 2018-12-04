<?php

namespace Auth\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth_refresh_tokens")
 */
class RefreshToken
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="string", length=100)
     * @ORM\GeneratedValue(strategy="NONE")
     * @var string
     */
    public $id;

    /**
     * @ORM\Column(name="access_token_id", type="string", length=100, nullable=true)
     * @var string
     */
    private $access_token_id;

    /**
     * @ORM\Column(name="revoked", type="boolean", nullable=true)
     * @var integer
     */
    private $revoked;

    /**
     * @ORM\Column(name="expires_at", type="datetime", nullable=true)
     * @var string
     */
    private $expires_at;
}