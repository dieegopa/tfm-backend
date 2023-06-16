<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    #[Route('/free/courses/{degree}', name: 'course', methods: ['GET'])]
    public function indexCourseDegrees(CourseRepository $courseRepository, SerializerInterface $serializer, $degree): Response
    {

        $courses = $courseRepository->createQueryBuilder('c')
            ->join('c.degree', 'd')
            ->where('d.slug = :degree')
            ->setParameter('degree', $degree)
            ->getQuery()
            ->getResult();

        if (!$courses) {
            return new Response(json_encode(['message' => 'Not Found']), 404, ['Content-Type' => 'application/json']);
        }

        $response = $serializer->serialize($courses, 'json');

        return new Response($response, 200, [
            'Content-Type' => 'application/json'
        ]);

    }

    #[Route('/free/courses/{degree}', name: 'options_courses', methods: ['OPTIONS'])]
    public function optionsCourses(): Response
    {
        $response =  new Response(null, 204, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type',
        ]);

        return $response->send();
    }
}
