<?php

namespace App\Controller;

use App\Repository\UserRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/api/users/{sub}', name: 'index_user', methods: ['GET'])]
    public function indexUser(UserRepository $userRepository, SerializerInterface $serializer, Request $request, $sub)
    {
        $user = $userRepository->findOneBy(['sub' => $sub]);

        if (!$user) {
            return new Response(json_encode(['message' => 'Not Found']), 404, ['Content-Type' => 'application/json']);
        }

        $response = $serializer->serialize($user, 'json');

        return new Response($response, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
}
