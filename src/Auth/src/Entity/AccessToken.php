<?php

declare(strict_types = 1);

namespace Auth\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="user_id", type="integer")
     * @var integer
     */
    private $user_id;

    /**
     * @ORM\Column(name="client_id", type="integer")
     * @var integer
     */
    private $client_id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="scopes", type="text")
     * @var string
     */
    private $scopes;

    /**
     * @ORM\Column(name="revoked", type="boolean")
     * @var boolean
     */
    private $revoked;

    /**
     * @ORM\Column(name="created_at", type="timestamp")
     * @var string
     */
    private $created_at;

    /**
     * @ORM\Column(name="updated_at", type="timestamp")
     * @var string
     */
    private $updated_at;

    /**
     * @ORM\Column(name="expires_at", type="timestamp")
     * @var string
     */
    private $expires_at;

}