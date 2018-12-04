<?php

declare(strict_types = 1);

namespace Auth\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth_scopes")
 */
class Scope extends Entity
{

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="string", length=30)
     * @ORM\GeneratedValue(strategy="NONE")
     * @var string
     */
    public $id;

}