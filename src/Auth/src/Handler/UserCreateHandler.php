<?php

declare(strict_types=1);

namespace Auth\Handler;

use Auth\Entity\Client;
use Auth\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class UserCreateHandler implements RequestHandlerInterface
{
    /**
    * @var EntityManagerInterface
    */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        try {
            $data = $request->getParsedBody();
            $inputFilter = User::getInputFilter();
            $inputFilter->setData($data);
            if ($inputFilter->isValid()) {
                $data = $inputFilter->getValues();

                $client = new Client([
                    'secret' => $data['client_secret'],
                    'name'   => $data['client_id']
                ]);

                $this->entityManager->persist($client);

                $data['client'] = $client;

                $user = new User($data);

                $this->entityManager->persist($user);
                $this->entityManager->flush();
            }
            else {
                return new JsonResponse(['error' => 'Invalid data'], 422);
            }
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            return new JsonResponse(['info' => sprintf('User with name %s already exists', $data['username'])], 422);
        }

        return new JsonResponse([
            'info' => sprintf('You have created %s', $user->getUsername()),
        ]);
    }
}
