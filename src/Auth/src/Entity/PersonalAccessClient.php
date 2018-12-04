<?php

namespace Auth\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth_personal_access_clients")
 */
class PersonalAccessClient
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="NONE")
     * @var string
     */
    public $client_id;

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
}