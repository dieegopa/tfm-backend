<?php

namespace App\Controller;

use App\Repository\DegreeRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DegreeController extends AbstractController
{
    #[Route('/free/degrees', name: 'degrees', methods: ['GET'])]
    public function index(DegreeRepository $degreeRepository, SerializerInterface $serializer, Request $request): Response
    {
        $query = $request->query->get('uni');
        if (isset($query)) {
            $degrees = $degreeRepository->createQueryBuilder('d')
                ->join('d.university', 'u')
                ->where('u.slug = :query')
                ->setParameter('query', $query)
                ->getQuery()
                ->getResult();

            $response = $serializer->serialize($degrees, 'json');
            return new Response($response, 200, [
                'Content-Type' => 'application/json'
            ]);
        }
        $degrees = $degreeRepository->findAll();
        $response = $serializer->serialize($degrees, 'json');

        return new Response($response, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

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
}
