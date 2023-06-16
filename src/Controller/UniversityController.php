<?php

namespace App\Controller;

use App\Repository\UniversityRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UniversityController extends AbstractController
{
    #[Route('/free/universities', name: 'universities', methods: ['GET'])]
    public function index(UniversityRepository $universityRepository, SerializerInterface $serializer, Request $request): Response
    {
        $query = $request->query->get('search');

        if (isset($query)) {

            $university = $universityRepository->createQueryBuilder('u')
                ->where('u.name LIKE :query')
                ->setParameter('query', '%' . $query . '%')
                ->getQuery()
                ->getResult();

            return new Response($serializer->serialize($university, 'json'), 200, [
                'Content-Type' => 'application/json'
            ]);

        }
        $universities = $universityRepository->findAll();
        $response = $serializer->serialize($universities, 'json');

        return new Response($response, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    #[Route('/free/universities/{slug}', name: 'university', methods: ['GET'])]
    public function indexUniversity(UniversityRepository $universityRepository, SerializerInterface $serializer, $slug): Response
    {
        $university = $universityRepository->findOneBy(['slug' => $slug]);

        if (!$university) {
            return new Response(json_encode(['message' => 'Not Found']), 404, ['Content-Type' => 'application/json']);
        }

        $response = $serializer->serialize($university, 'json');

        return new Response($response, 200, [
            'Content-Type' => 'application/json'
        ]);

    }

    #[Route('/api/universities/favorite', name: 'set_favorite_university', methods: ['PATCH'])]
    public function favoriteUniversity(ManagerRegistry $doctrine, UniversityRepository $universityRepository, UserRepository $userRepository, Request $request)
    {
        $decoded = json_decode($request->getContent());
        $universityId = $decoded->university_id ?? null;
        $userSub = $decoded->user_sub ?? null;

        if (!isset($universityId) || !isset($userSub)) {
            return new Response(json_encode(['message' => 'Invalid Request']), 412, ['Content-Type' => 'application/json']);
        }

        $user = $userRepository->findOneBy(['sub' => $userSub]);
        $university = $universityRepository->find($universityId);

        if (!$user || !$university) {
            return new Response(json_encode(['message' => 'Not Found']), 404, ['Content-Type' => 'application/json']);
        }

        $userUniversity = $user->getUniversities()->filter(function ($university) use ($universityId) {
            return $university->getId() === $universityId;
        });

        if ($userUniversity->count() > 0) {
            $user->getUniversities()->removeElement($university);
            $university->getUsers()->removeElement($user);
            $favorite = false;
        } else {
            $user->getUniversities()->add($university);
            $university->getUsers()->add($user);
            $favorite = true;
        }

        $em = $doctrine->getManager();
        $em->persist($user);
        $em->persist($university);
        $em->flush();

        return new Response(json_encode(['favorite' => $favorite]), 200, ['Content-Type' => 'application/json']);

    }

    #[Route('/api/universities/favorite/{universityId}/{userSub}', name: 'is_favorite_university', methods: ['GET'])]
    public function isFavoriteUniversity(UniversityRepository $universityRepository, $universityId, $userSub)
    {
        $university = $universityRepository->find($universityId);

        if (!$university) {
            return new Response(json_encode(['message' => 'Not Found']), 404, ['Content-Type' => 'application/json']);
        }

        $userUniversity = $university->getUsers()->filter(function ($user) use ($userSub) {
            return $user->getSub() === $userSub;
        });

        if ($userUniversity->count() > 0) {
            return new Response(json_encode(['favorite' => true]), 200, ['Content-Type' => 'application/json']);
        }

        return new Response(json_encode(['favorite' => false]), 200, ['Content-Type' => 'application/json']);

    }

    #[Route('/free/universities', name: 'options_universities_free', methods: ['OPTIONS'])]
    public function optionsUniversitiesFree(): Response
    {
        $response = new Response(null, 204, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        ]);

        return $response->send();

    }

    #[Route('/free/universities/{slug}', name: 'options_universities_free_slug', methods: ['OPTIONS'])]
    public function optionsUniversitiesFreeSlug(): Response
    {
        $response = new Response(null, 204, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        ]);

        return $response->send();

    }

    #[Route('/api/universities/favorite', name: 'options_universities_favorite', methods: ['OPTIONS'])]
    public function optionsUniversitiesFavorite(): Response
    {
        $response = new Response(null, 204, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'PATCH, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        ]);

        return $response->send();

    }

    #[Route('/api/universities/favorite/{universityId}/{userSub}', name: 'options_universities_uni_user', methods: ['OPTIONS'])]
    public function optionsUniversitiesUniUser(): Response
    {
        $response = new Response(null, 204, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        ]);

        return $response->send();

    }


}
