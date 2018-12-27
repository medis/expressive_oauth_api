<?php

declare(strict_types=1);

namespace App\Factory;

use Psr\Container\ContainerInterface;
use Tuupola\Middleware\CorsMiddleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class CorsMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : CorsMiddleware
    {
        return new CorsMiddleware([
            "origin" => ["*"],
            "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
            "headers.allow" => [
                'Origin', 'X-Requested-With', 'Content-Type', 'Accept', 'Authorization'
            ],
            "headers.expose" => [],
            "credentials" => false,
            "cache" => 0,
            "error" => function (RequestInterface $request, ResponseInterface $response, $arguments) {
                return new JsonResponse($arguments);
            }
        ]);
    }
}
