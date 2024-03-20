<?php

namespace App\Controller;

use App\Entity\User;
use App\Message\UserCreated;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UserController extends AbstractController
{
    #[Route('/users', name: 'app_user', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    #[Route('/users', name: 'create_user', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator, SerializerInterface $serializer, MessageBusInterface $messageBus): JsonResponse
    {
        // $jsonData = json_decode($request->getContent(), true);
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        // $user = new User();
        // $user->setEmail($jsonData['email']);
        // $user->setFirstName($jsonData['firstName']);
        // $user->setLastName($jsonData['lastName']);

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            // Handle validation errors
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        }

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        $jsonData = json_decode($request->getContent(), true);
        $messageBus->dispatch(
            message: new UserCreated($jsonData)
        );

        // return new Response('Saved new product with id '.$product->getId());
        return $this->json([
            'message' => 'New user created!',
        ]);
    }
}
