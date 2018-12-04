<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;

/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/:id', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Zend\Expressive\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container) : void {

    $uuidPattern = '\b[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}\b';

    $app->get('/', App\Handler\HomePageHandler::class, 'home');

    $app->post('/api/ping', [
        Zend\Expressive\Authentication\AuthenticationMiddleware::class,
        App\Handler\PingHandler::class
    ], 'api.ping');

    $app->post('/api/user/create', [
        Auth\Handler\UserCreateHandler::class
    ], 'user.create');

    $app->get(sprintf('/api/user/{id:%s}', $uuidPattern), [
        Auth\Handler\UserShowHandler::class
    ], 'user.show');

    $app->post(sprintf('/api/user/{id:%s}', $uuidPattern), [
        Auth\Handler\UserPatchHandler::class
    ], 'user.patch');

    $app->post('/oauth', \Zend\Expressive\Authentication\OAuth2\TokenEndpointHandler::class, 'oauth-token');
};
