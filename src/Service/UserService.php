<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserService
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected ValidatorInterface $validator,
        protected SerializerInterface $serializer
    ) {
    }

    public function createUser($request)
    {
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $errors = self::validate($user);

        if (count($errors) > 0) {
            // Handle validation errors
            throw new \Exception('Validation failed');
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return true;
    }

    private function validate($user)
    {
        $violations = $this->validator->validate($user);
        $errorMessages = [];

        if ($violations->count() > 0) {
            foreach ($violations as $error) {
                $errorMessages[] = $error->getMessage();
            }
        }

        return $errorMessages;
    }
}
