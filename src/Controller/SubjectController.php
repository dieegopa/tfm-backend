<?php

namespace App\Controller;

use App\Repository\SubjectRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/api/subjects/favorite', name: 'set_favorite_subject', methods: ['PATCH'])]
    public function favoriteSubject(ManagerRegistry $doctrine, SubjectRepository $subjectRepository, UserRepository $userRepository, Request $request)
    {
        $decoded = json_decode($request->getContent());
        $subjectId = $decoded->subject_id ?? null;
        $userSub = $decoded->user_sub ?? null;

        if (!$subjectId || !$userSub) {
            return new Response(json_encode(['message' => 'Bad Request']), 412, ['Content-Type' => 'application/json']);
        }

        $subject = $subjectRepository->find($subjectId);
        $user = $userRepository->findOneBy(['sub' => $userSub]);

        if (!$subject || !$user) {
            return new Response(json_encode(['message' => 'Not Found']), 404, ['Content-Type' => 'application/json']);
        }

        $userSubject = $user->getSubjects()->filter(function ($subject) use ($subjectId) {
            return $subject->getId() === $subjectId;
        });

        if ($userSubject->count() > 0) {
            $user->removeSubject($subject);
            $subject->removeUser($user);
            $favorite = false;
        } else {
            $user->addSubject($subject);
            $subject->addUser($user);
            $favorite = true;
        }

        $em = $doctrine->getManager();
        $em->persist($user);
        $em->persist($subject);
        $em->flush();

        return new Response(json_encode(['favorite' => $favorite]), 200, ['Content-Type' => 'application/json']);

    }

    #[Route('/free/subjects/{subject}', name: 'options_subjects_subject', methods: ['OPTIONS'])]
    public function optionsSubjectsSubject(): Response
    {
        $response = new Response(null, 204, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        ]);

        return $response->send();

    }

    #[Route('/api/subjects/favorite', name: 'options_subjects_favorite', methods: ['OPTIONS'])]
    public function optionsSubjectsFavorite(): Response
    {
        $response = new Response(null, 204, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'PATCH, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        ]);

        return $response->send();

    }
}
