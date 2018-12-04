<?php

declare(strict_types = 1);

namespace Auth\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="oauth_clients")
 */
class Client extends Entity
{

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="NONE")
     * @var string
     */
    public $id;

    /**
     * @ORM\Column(name="name", type="string", length=1024)
     * @var string
     */
    public $name;

    /**
     * @ORM\Column(name="secret", type="string", length=1024, nullable=false)
     * @var string
     */
    private $secret;

    /**
     * @ORM\Column(name="redirect", type="string", length=1024)
     * @var string
     */
    public $redirect = '/redirect';

    /**
     * @ORM\Column(name="personal_access_client", type="boolean")
     * @var boolean
     */
    public $personal_access_client = true;

    /**
     * @ORM\Column(name="password_client", type="boolean")
     * @var boolean
     */
    public $password_client = true;

    /**
     * @ORM\Column(name="revoked", type="boolean")
     * @var boolean
     */
    private $revoked = false;

    /**
     * @ORM\Column(name="algorithm", type="integer", nullable=false)
     * @var integer
     */
    private $algorithm = PASSWORD_DEFAULT;

    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getRedirect(): string
    {
        return $this->redirect;
    }

    /**
     * @param string $redirect
     */
    public function setRedirect(string $redirect)
    {
        $this->redirect = $redirect;
    }

    /**
     * @return int
     */
    public function getPersonalAccessClient(): int
    {
        return $this->personal_access_client;
    }

    /**
     * @param int $personal_access_client
     */
    public function setPersonalAccessClient(int $personal_access_client)
    {
        $this->personal_access_client = $personal_access_client;
    }

    /**
     * @return int
     */
    public function getPasswordClient(): int
    {
        return $this->password_client;
    }

    /**
     * @param int $password_client
     */
    public function setPasswordClient(int $password_client)
    {
        $this->password_client = $password_client;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     */
    public function setSecret(string $secret)
    {
        $secret = (string) $secret;
        if ($this->secret !== $secret) {
            $this->hashSecretRequired = true;
        }
        $this->secret = $secret;
    }

    /**
     * @return int
     */
    public function getAlgorithm(): int
    {
        return $this->algorithm;
    }

    /**
     * @param int $algorithm
     */
    public function setAlgorithm(int $algorithm)
    {
        $this->algorithm = $algorithm;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name) : void
    {
        $this->name = $name;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function hashSecret(LifecycleEventArgs $args)
    {
        if (isset($this->hashSecretRequired)) {
            $secret = $args->getEntity()->getSecret();
            $this->secret = password_hash($secret, $this->algorithm);
            unset($this->hashSecretRequired);
        }
    }
}