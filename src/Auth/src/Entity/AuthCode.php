<?php

namespace Auth\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth_auth_codes")
 */
class AuthCode
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="string", length=100, nullable=false)
     * @ORM\GeneratedValue(strategy="NONE")
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
     * @ORM\Column(name="scopes", type="text")
     * @var string
     */
    private $scopes;

    /**
     * @ORM\Column(name="revoked", type="boolean")
     * @var integer
     */
    private $revoked;

    /**
     * @ORM\Column(name="expires_at", type="timestamp")
     * @var string
     */
    private $expires_at;
}