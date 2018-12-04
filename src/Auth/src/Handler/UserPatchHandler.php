<?php

declare(strict_types=1);

namespace Auth\Handler;

use Auth\Entity\User;
use Auth\Service\User\Exception\UserNotFound;
use Auth\Service\User\FindUserByUuidInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;
use Zend\Diactoros\Response\JsonResponse;

class UserPatchHandler implements RequestHandlerInterface
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
        }

        try {
            $this->entityManager->transactional(function() use ($user, $request) {
                $inputFilter = User::getInputFilter(false);
                $inputFilter->setData($request->getParsedBody());
                if ($inputFilter->isValid()) {
                    $data = $inputFilter->getValues();
                    $user->data($data);
                }
            });
        } catch (\Exception $e) {
            print_r($e->getMessage());die;
        }

        return new JsonResponse(['info' => sprintf('updated user %s', $user->getName())]);
    }
}
