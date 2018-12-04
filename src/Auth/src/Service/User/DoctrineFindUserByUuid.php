<?php

declare(strict_types = 1);

namespace Auth\Service\User;

use Doctrine\Common\Persistence\ObjectRepository;
use Auth\Entity\User;
use Ramsey\Uuid\UuidInterface;

class DoctrineFindUserByUuid implements FindUserByUuidInterface
{
    /**
     * @var ObjectRepository
     */
    private $repository;

    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UuidInterface $id
     * @return User
     * @throws Exception\UserNotFound
     */
    public function __invoke(UuidInterface $id): User
    {
        /** @var Item|null $item */
        $item = $this->repository->find((string)$id);
        if (null === $item) {
            throw Exception\UserNotFound::fromUuid($id);
        }
        return $item;
    }
}