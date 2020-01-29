<?php

declare(strict_types=1);

namespace App;

use ContainerInteropDoctrine\EntityManagerFactory;
use Doctrine\ORM\EntityManager;

/**
 * The configuration provider for the App module
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
     *
     */
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
            'doctrine' => $this->getDatabaseConfig()
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'invokables' => [
                Handler\PingHandler::class => Handler\PingHandler::class,
            ],
            'factories'  => [
                Handler\HomePageHandler::class => Handler\HomePageHandlerFactory::class,
                \Tuupola\Middleware\CorsMiddleware::class => \App\Factory\CorsMiddlewareFactory::class,
                EntityManager::class => EntityManagerFactory::class
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                'app'    => ['templates/app'],
                'error'  => ['templates/error'],
                'layout' => ['templates/layout'],
            ],
        ];
    }

    public function getDatabaseConfig()
    {
        return [
            'connection' => [
                'orm_default' => [
                    'params' => [
                        'driver' => 'pdo_mysql',
                        'host' => 'mysql:3306',
                        'dbname' => 'oauth',
                        'user' => 'root',
                        'password' => 'password',
                    ]
                ]
            ],
        ];
    }

}
