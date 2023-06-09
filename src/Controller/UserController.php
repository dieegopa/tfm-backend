<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
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

    #[Route('/api/users/{sub}', name: 'delete_user', methods: ['DELETE'])]
    public function deleteUser(ManagerRegistry $doctrine, UserRepository $userRepository, SerializerInterface $serializer, Request $request, $sub)
    {

        $user = $userRepository->findOneBy(['sub' => $sub]);

        if (!$user) {
            return new Response(json_encode(['message' => 'Not Found']), 404, ['Content-Type' => 'application/json']);
        }

        $em = $doctrine->getManager();
        $em->remove($user);
        $em->flush();

        return new Response(json_encode(['message' => 'Deleted']), 200, ['Content-Type' => 'application/json']);

    }

    #[Route('/users', name: 'options_users', methods: ['OPTIONS'])]
    public function optionsUsers(): Response
    {
        $response = new Response(null, 200, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        ]);

        return $response->send();

    }

}
