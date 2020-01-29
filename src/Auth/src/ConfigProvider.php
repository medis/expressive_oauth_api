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
use Zend\Expressive\Authentication\AuthenticationInterface;
use Zend\Expressive\Authentication\Session\PhpSession;
use Zend\Expressive\Authentication\UserRepository\PdoDatabase;
use Zend\Expressive\Authentication\UserRepositoryInterface;

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
                Authentication\AuthenticationInterface::class => Authentication\OAuth2\OAuth2Adapter::class,
                // AuthenticationInterface::class => PhpSession::class,
                UserRepositoryInterface::class => PdoDatabase::class
            ],
            'factories' => [
                EntityManagerInterface::class => EntityManagerFactory::class,
                User\FindUserByUuidInterface::class => User\DoctrineFindUserByUuidFactory::class,
                Handler\UserLoginHandler::class => Handler\UserLoginHandlerFactory::class,
                Handler\UserLogoutHandler::class => Handler\UserLogoutHandlerFactory::class,
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
                'password' => 'password',
                'table' => 'oauth_users',
                'field' => [
                    'identity' => 'username',
                    'password' => 'password',
                ],
                'sql_get_details' => 'SELECT first_name, last_name FROM oauth_users WHERE username = :identity'
            ],
            'redirect' => '/login',
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
