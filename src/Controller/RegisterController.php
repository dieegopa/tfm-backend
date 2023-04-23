<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class RegisterController extends AbstractController
{
    #[Route('/register', name: 'register', methods: ['POST'])]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $em = $doctrine->getManager();
        $decoded = json_decode($request->getContent());
        $email = $decoded->email ?? null;
        $sub = $decoded->sub ?? null;

        if(isset($email) && isset($sub)) {

            try {
                $user = new User();
                $user->setEmail($email);
                $user->setSub($sub);
                $em->persist($user);
                $em->flush();
            } catch (\Exception $e) {
                $em = $doctrine->resetManager();
                $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);
                if(!$user){
                    return new Response(json_encode(['message' => 'Not Found']), 404, ['Content-Type' => 'application/json']);
                }
                $user->setSub($sub);
                $em->flush();

                return new Response(json_encode(['message' => 'Registered Successfully']), 200, ['Content-Type' => 'application/json']);
            }

            return new Response(json_encode(['message' => 'Registered Successfully']), 200, ['Content-Type' => 'application/json']);
        }

        return new Response(json_encode(['message' => 'Invalid Request']), 200, ['Content-Type' => 'application/json']);

    }
}
