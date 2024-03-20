<?php

namespace App\Controller;

use App\Entity\User;
use App\Message\UserCreated;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UserController extends AbstractController
{
    public function __construct(protected UserService $userService)
    {
    }
    
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
        try {
            $this->userService->createUser($request);

            $jsonData = json_decode($request->getContent(), true);
            $messageBus->dispatch(
                message: new UserCreated($jsonData)
            );
        } catch (Exception $e) {
            return $this->json([
                'message' => "Failed to create user! " . $e->getMessage(),
            ], 500);
        }

        // return new Response('Saved new product with id '.$product->getId());
        return $this->json([
            'message' => 'New user created!',
        ]);
    }
}
