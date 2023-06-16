<?php

namespace App\Controller;

use App\Repository\DegreeRepository;
use App\Repository\UniversityRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DegreeController extends AbstractController
{

    #[Route('/free/degrees/{university}/{slug}', name: 'degree', methods: ['GET'])]
    public function indexDegree(DegreeRepository $degreeRepository, SerializerInterface $serializer, $university, $slug): Response
    {
        $degree = $degreeRepository->createQueryBuilder('d')
            ->join('d.university', 'u')
            ->where('u.slug = :university')
            ->andWhere('d.slug = :slug')
            ->setParameter('university', $university)
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getResult();

        if (!$degree) {
            return new Response(json_encode(['message' => 'Not Found']), 404, ['Content-Type' => 'application/json']);
        }

        $response = $serializer->serialize($degree, 'json');

        return new Response($response, 200, [
            'Content-Type' => 'application/json'
        ]);

    }

    #[Route('/api/degrees/favorite', name: 'set_favorite_degree', methods: ['PATCH'])]
    public function favoriteDegree(ManagerRegistry $doctrine, DegreeRepository $degreeRepository, UserRepository $userRepository, Request $request)
    {
        $decoded = json_decode($request->getContent());
        $degreeId = $decoded->degree_id ?? null;
        $userSub = $decoded->user_sub ?? null;

        if (!$degreeId || !$userSub) {
            return new Response(json_encode(['message' => 'Missing parameters']), 412, ['Content-Type' => 'application/json']);
        }

        $degree = $degreeRepository->find($degreeId);
        $user = $userRepository->findOneBy(['sub' => $userSub]);

        if(!$degree || !$user) {
            return new Response(json_encode(['message' => 'Not Found']), 404, ['Content-Type' => 'application/json']);
        }

        $userDegree = $user->getDegrees()->filter(function($degree) use ($degreeId) {
            return $degree->getId() === $degreeId;
        });

        if($userDegree->count() > 0) {
            $user->removeDegree($degree);
            $degree->removeUser($user);
            $favorite = false;
        } else {
            $user->addDegree($degree);
            $degree->addUser($user);
            $favorite = true;
        }

        try {
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->persist($degree);
            $em->flush();
        } catch (\Exception $e) {
            return new Response(json_encode(['favorite' => true]), 500, ['Content-Type' => 'application/json']);
        }

        return new Response(json_encode(['favorite' => $favorite]), 200, ['Content-Type' => 'application/json']);

    }

    #[Route('free/degrees', name: 'options_degrees', methods: ['OPTIONS'])]
    public function optionsDegrees(): Response
    {
        $response =  new Response(null, 204, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        ]);

        return $response->send();

    }

    #[Route('/free/degrees/{university}/{slug}', name: 'options_degrees_university', methods: ['OPTIONS'])]
    public function optionsDegreesUniversity(): Response
    {
        $response =  new Response(null, 204, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        ]);

        return $response->send();

    }

    #[Route('/api/degrees/favorite', name: 'options_degrees_favorite', methods: ['OPTIONS'])]
    public function optionsDegreesFavorite(): Response
    {
        $response =  new Response(null, 204, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'PATCH, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        ]);

        return $response->send();

    }

}
