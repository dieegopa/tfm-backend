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
    public function indexUser(UserRepository $userRepository, SerializerInterface $serializer, $sub)
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
    public function deleteUser(ManagerRegistry $doctrine, UserRepository $userRepository, $sub)
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

    #[Route('/api/register', name: 'options_users_register', methods: ['OPTIONS'])]
    public function optionsUsersRegister(): Response
    {
        $response = new Response(null, 204, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'POST, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        ]);

        return $response->send();

    }

    #[Route('/api/users/{sub}', name: 'options_users_sub', methods: ['OPTIONS'])]
    public function optionsUsersSub(): Response
    {
        $response = new Response(null, 204, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        ]);

        return $response->send();

    }

}
