<?php

namespace App\Controller;

use App\Entity\Rating;
use App\Repository\FileRepository;
use App\Repository\RatingRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RatingController extends AbstractController
{
    #[Route('/api/ratings', name: 'post_rating', methods: ['PATCH'])]
    public function saveRating(ManagerRegistry $doctrine, FileRepository $fileRepository, RatingRepository $ratingRepository, UserRepository $userRepository, Request $request, SerializerInterface $serializer): Response
    {
        $em = $doctrine->getManager();
        $decoded = json_decode($request->getContent());
        $fileId = $decoded->file_id ?? null;
        $userSub = $decoded->user_sub ?? null;
        $value = $decoded->value ?? null;

        if (!$fileId || !$userSub) {
            return new Response(json_encode(['message' => 'Bad Request']), 412, ['Content-Type' => 'application/json']);
        }

        $file = $fileRepository->find($fileId);
        $user = $userRepository->findOneBy(['sub' => $userSub]);

        if (!$file || !$user) {
            return new Response(json_encode(['message' => 'Not Found']), 404, ['Content-Type' => 'application/json']);
        }

        $rating = $ratingRepository->createQueryBuilder('r')
            ->join('r.file', 'f')
            ->join('r.user', 'u')
            ->where('f.id = :fileId')
            ->andWhere('u.sub = :userSub')
            ->setParameter('fileId', $fileId)
            ->setParameter('userSub', $userSub)
            ->getQuery()
            ->getResult();

        if (!$rating) {
            $rating = new Rating();
            $rating->setFile($file);
            $rating->setUser($user);
            $rating->setValue($value);
            $user->getRatings()->add($rating);
            $file->getRatings()->add($rating);
            $em->persist($rating);
            $em->persist($user);
            $em->persist($file);
            $em->flush();
        } else {
            $rating[0]->setValue($value);
            $em->persist($rating[0]);
            $em->flush();
        }

        $file = $fileRepository->createQueryBuilder('f')
            ->join('f.user', 'u')
            ->leftJoin('f.ratings', 'r')
            ->where('f.id = :id')
            ->setParameter('id', $fileId)
            ->select('f.id', 'f.name', 'f.category', 'f.type', 'f.extra', 'f.url', 'u.sub as user', 'AVG(r.value) as rating')
            ->getQuery()
            ->getSingleResult();

        $response = $serializer->serialize($file, 'json');

        return new Response($response, 200, ['Content-Type' => 'application/json']);

    }

    #[Route('/api/ratings/{fileId}/{userSub}', name: 'index_file_rating', methods: ['GET'])]
    public function indexFileRating(RatingRepository $ratingRepository, SerializerInterface $serializer, $fileId, $userSub)
    {

        try {
            $rating = $ratingRepository->createQueryBuilder('r')
                ->join('r.file', 'f')
                ->join('r.user', 'u')
                ->where('f.id = :fileId')
                ->andWhere('u.sub = :userSub')
                ->setParameter('fileId', $fileId)
                ->setParameter('userSub', $userSub)
                ->select('r.value as rating')
                ->getQuery()
                ->getSingleResult();
        } catch (\Exception $e) {
            return new Response(json_encode(['rating' => 0]), 200, ['Content-Type' => 'application/json']);
        }

        if (!$rating) {
            return new Response(json_encode(['rating' => 0]), 404, ['Content-Type' => 'application/json']);
        } else {
            $response = $serializer->serialize($rating, 'json');
            return new Response($response, 200, ['Content-Type' => 'application/json']);
        }

    }

    #[Route('/api/ratings', name: 'options_ratings', methods: ['OPTIONS'])]
    public function optionsRatings(): Response
    {
        $response = new Response(null, 204, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'PATCH, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        ]);

        return $response->send();

    }

    #[Route('/api/ratings/{fileId}/{userSub}', name: 'options_ratings_file', methods: ['OPTIONS'])]
    public function optionsRatingsFile(): Response
    {
        $response = new Response(null, 204, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        ]);

        return $response->send();

    }

}
