<?php
/**
 * This file generated by Zend\Expressive\Tooling\Factory\ConfigInjector.
 *
 * Modifications should be kept at a minimum, and restricted to adding or
 * removing factory definitions; other dependency types may be overwritten
 * when regenerating this file via zend-expressive-tooling commands.
 */
 
declare(strict_types=1);

return [
    'dependencies' => [
        'factories' => [
            Auth\Handler\UserCreateHandler::class => Auth\Handler\UserCreateHandlerFactory::class,
            Auth\Handler\UserPatchHandler::class => Auth\Handler\UserPatchHandlerFactory::class,
            Auth\Handler\UserShowHandler::class => Auth\Handler\UserShowHandlerFactory::class,
            Auth\UserLoginHandler::class => Auth\UserLoginHandlerFactory::class,
            Dashboard\Handler\LandingPageHandler::class => Dashboard\Handler\LandingPageHandlerFactory::class,
            Dashboard\Handler\TokensPageHandler::class => Dashboard\Handler\TokensPageHandlerFactory::class,
        ],
    ],
];
