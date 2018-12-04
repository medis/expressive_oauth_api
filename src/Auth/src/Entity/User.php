<?php

declare(strict_types = 1);

namespace Auth\Entity;

use Auth\Entity\Exception\UnsupportedHashAlgorithm;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\Factory as InputFilterFactory;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="oauth_users")
 */
class User extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="NONE")
     * @var string
     */
    public $id;

    /**
     * @ORM\Column(name="username", type="string", length=512, nullable=false, unique=true)
     * @var string
     */
    public $username;

    /**
     * @ORM\Column(name="password", type="string", length=1024, nullable=false)
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(name="first_name", type="string", length=1024)
     * @var string
     */
    public $first_name;

    /**
     * @ORM\Column(name="last_name", type="string", length=1024)
     * @var string
     */
    public $last_name;

    /**
     * @ORM\Column(name="algorithm", type="integer", nullable=false)
     * @var integer
     */
    private $algorithm = PASSWORD_DEFAULT;

    /**
     * @ORM\OneToOne(targetEntity="Client", fetch="EAGER")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     * @var integer
     */
    public $client;

    /**
     * When updating existing content, required flag is not needed.
     * @param bool $is_new
     * @return \Zend\InputFilter\InputFilterInterface
     */
    public static function getInputFilter($is_new = true)
    {
        $specification = [
            'username' => [
                'required' => $is_new,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 3,
                            'max' => 1024,
                        ],
                    ],
                ]
            ],

            'client_id' => [
                'required' => $is_new,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 3,
                            'max' => 1024,
                        ],
                    ],
                ]
            ],

            'client_secret' => [
                'required' => $is_new,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 3,
                            'max' => 1024,
                        ],
                    ],
                ]
            ],

            'first_name' => [
                'required' => false,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 3,
                            'max' => 1024,
                        ],
                    ],
                ]
            ],

            'last_name' => [
                'required' => false,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 3,
                            'max' => 1024,
                        ],
                    ],
                ]
            ],

            'password' => [
                'required' => $is_new,
                'filters' => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 3,
                            'max' => 1024,
                        ],
                    ],
                ]
            ]
        ];

        $factory = new InputFilterFactory();
        $inputFilter = $factory->createInputFilter($specification);

        return $inputFilter;
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function setUsername(string $name) : void
    {
        $this->username = $name;
    }

    public function getUsername() : string
    {
        return $this->username;
    }

    public function setFirstName(string $first_name) : void
    {
        $this->first_name = $first_name;
    }

    public function getFirstName() : string
    {
        return $this->first_name;
    }

    public function setLastName(string $last_name) : void
    {
        $this->last_name = $last_name;
    }

    public function getLastName() : string
    {
        return $this->last_name;
    }

    public function setPassword($password)
    {
        $password = (string) $password;
        if ($this->password !== $password) {
            $this->hashPasswordRequired = true;
        }
        $this->password = $password;
    }

    public function getPassword() : string
    {
        return $this->password;
    }

    public function setEmail(string $email) : void
    {
        $this->email = $email;
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function setAlgorithm($algorithm)
    {
        $algorithm = (string) $algorithm;
        if (!in_array($algorithm, hash_algos())) {
            throw UnsupportedHashAlgorithm::fromAlgorithm($algorithm);
        }
        $this->algorithm = $algorithm;
    }

    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    public function getClient() : Client
    {
        return $this->client;
    }

//    public function checkPassword($password) {
//        return password_verify($password, $this->password);
//    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function hashPassword(LifecycleEventArgs $args)
    {
        if (isset($this->hashPasswordRequired)) {
            $password = $args->getEntity()->getPassword();
            $this->password = password_hash($password, $this->algorithm);
            unset($this->hashPasswordRequired);
        }
    }
}