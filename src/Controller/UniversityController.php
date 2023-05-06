<?php

namespace App\Controller;

use App\Repository\UniversityRepository;
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


}
