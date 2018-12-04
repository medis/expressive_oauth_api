<?php

declare(strict_types = 1);

namespace Auth\Entity;

use Doctrine\ORM\Mapping as ORM;
use spec\Prophecy\Argument\Token\StringContainsTokenSpec;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth_access_tokens")
 */
class AccessToken
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="string", length=100)
     * @var string
     */
    public $id;

    /**
     * @ORM\Column(name="user_id", type="string", length=40, nullable=true)
     * @var string
     */
    private $user_id;

    /**
     * @ORM\Column(name="client_id", type="string", length=40, nullable=true)
     * @var string
     */
    private $client_id;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="scopes", type="text", nullable=true)
     * @var string
     */
    private $scopes;

    /**
     * @ORM\Column(name="revoked", type="boolean", nullable=true)
     * @var boolean
     */
    private $revoked;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     * @var string
     */
    private $created_at;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @var string
     */
    private $updated_at;

    /**
     * @ORM\Column(name="expires_at", type="datetime", nullable=true)
     * @var string
     */
    private $expires_at;

}