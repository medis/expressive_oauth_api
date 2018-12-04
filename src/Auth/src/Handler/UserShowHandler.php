<?php

declare(strict_types=1);

namespace Auth\Handler;

use Auth\Service\User\Exception\UserNotFound;
use Auth\Service\User\FindUserByUuidInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use Zend\Diactoros\Response\JsonResponse;

class UserShowHandler implements RequestHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FindUserByUuidInterface
     */
    private $findUserByUuid;

    public function __construct(EntityManagerInterface $entityManager, FindUserByUuidInterface $findUserByUuid)
    {
        $this->entityManager = $entityManager;
        $this->findUserByUuid = $findUserByUuid;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        try {
            $user = $this->findUserByUuid->__invoke(Uuid::fromString($request->getAttribute('id')));
        } catch (UserNotFound $userNotFound) {
            return new JsonResponse(['error' => $userNotFound->getMessage()], 404);
        } catch (InvalidUuidStringException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 422);
        }

        return new JsonResponse($user);
    }
}
