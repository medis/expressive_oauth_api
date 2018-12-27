<?php

declare(strict_types=1);

namespace Auth\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class UserLoginHandler implements RequestHandlerInterface
{
    public function __construct()
    {
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return new JsonResponse(['info' => 'hi']);
    }
}
