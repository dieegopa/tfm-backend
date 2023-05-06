<?php

namespace App\Controller;

use App\Repository\SubjectRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubjectController extends AbstractController
{
    #[Route('/free/subjects/{subject}', name: 'subject', methods: ['GET'])]
    public function indexSubjects(SubjectRepository $subjectRepository, SerializerInterface $serializer, $subject): Response
    {
        $subjects = $subjectRepository->findOneBy(['slug' => $subject]);

        if (!$subjects) {
            return new Response(json_encode(['message' => 'Not Found']), 404, ['Content-Type' => 'application/json']);
        }

        $response = $serializer->serialize($subjects, 'json');

        return new Response($response, 200, [
            'Content-Type' => 'application/json'
        ]);

    }
}
