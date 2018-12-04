<?php

declare(strict_types=1);

namespace Auth;

use Zend\Expressive\Authentication;
use ContainerInteropDoctrine\EntityManagerFactory;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\DBAL\Driver\Mysqli\Driver;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Auth\Service\User;

/**
 * The configuration provider for the Auth module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke() : array
    {
        return [
            'dependencies'   => $this->getDependencies(),
            'doctrine'       => $this->getDoctrine(),
            'authentication' => $this->getAuthenticationConfig(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'aliases' => [
                Authentication\AuthenticationInterface::class => Authentication\OAuth2\OAuth2Adapter::class
            ],
            'factories' => [
                EntityManagerInterface::class => EntityManagerFactory::class,
                User\FindUserByUuidInterface::class => User\DoctrineFindUserByUuidFactory::class,
            ],
        ];
    }

    public function getAuthenticationConfig()
    {
        return [
            'private_key'    => getcwd() . '/data/oauth/private.key',
            'public_key'     => getcwd() . '/data/oauth/public.key',
            'encryption_key' => require getcwd() . '/data/oauth/encryption.key',
            'access_token_expire'  => 'P1D',
            'refresh_token_expire' => 'P1M',
            'auth_code_expire'     => 'PT10M',
            'pdo' => [
                'dsn'      => 'mysql:dbname=oauth;host=mysql;charset=utf8',
                'username' => 'root',
                'password' => 'password'
            ],
        ];
    }

    private function getDoctrine(): array
    {
        return [
            'connection' => [
                'orm_default' => [
                    'driver_class' => Driver::class
                ],
            ],
            'driver' => [
                'orm_default' => [
                    'class' => MappingDriverChain::class,
                    'drivers' => [
                        'Auth\Entity' => 'app_entity',
                    ],
                ],
                'app_entity' => [
                    'class' => AnnotationDriver::class,
                    'cache' => 'array',
                    'paths' => [
                        __DIR__ . '/Entity',
                    ],
                ],
            ],
        ];
    }
}
