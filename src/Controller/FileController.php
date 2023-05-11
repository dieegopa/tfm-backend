<?php

namespace App\Controller;

use App\Entity\File;
use App\Repository\FileRepository;
use App\Repository\SubjectRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileController extends AbstractController
{

    #[Route('/api/files/upload', name: 'upload_files', methods: ['POST'])]
    public function uploadFile(ManagerRegistry $doctrine, SubjectRepository $subjectRepository, UserRepository $userRepository, Request $request)
    {
        $fileName = $request->request->get('fileName');
        $uniqueName = $request->request->get('uniqueName');
        $fileCategory = $request->request->get('fileCategory');
        $fileExtra = $request->request->get('fileExtra');
        $fileType = $request->request->get('fileType');
        $subjectId = $request->request->get('subjectId');
        $userSub = $request->request->get('userSub');
        $fileUrl = $request->request->get('fileUrl');

        $user = $userRepository->findOneBy(['sub' => $userSub]);
        $subject = $subjectRepository->find($subjectId);
        if (!$user || !$subject) {
            return new Response(json_encode(['message' => 'Not Found']), 404, ['Content-Type' => 'application/json']);
        }

        $em = $doctrine->getManager();

        try {
            $file = new File();
            $file->setName($fileName);
            $file->setCategory($fileCategory);
            $file->setUser($user);
            $file->setType($fileType);
            $file->setExtra($fileExtra);
            $file->setSubject($subject);
            $file->setUrl($fileUrl);
            $file->setUniqueName($uniqueName);

            $em->persist($file);
            $em->flush();
        } catch (\Exception $e) {
            return new Response(json_encode(['message' => $e->getMessage()]), 412, ['Content-Type' => 'application/json']);
        }


        return new Response(json_encode(['message' => ['Uploaded']]), 200, ['Content-Type' => 'application/json']);

    }

    #[Route('/api/files/user/{sub}', name: 'index_user_files', methods: ['GET'])]
    public function indexUserFiles(FileRepository $fileRepository, SerializerInterface $serializer, $sub)
    {
        $files = $fileRepository->createQueryBuilder('f')
            ->join('f.user', 'u')
            ->where('u.sub = :sub')
            ->setParameter('sub', $sub)
            ->select('f.id', 'f.name', 'f.category', 'f.type', 'f.extra', 'f.url', 'u.email as user')
            ->getQuery()
            ->getResult();

        $response = $serializer->serialize($files, 'json');

        return new Response($response, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    #[Route('/api/files/university/{id}', name: 'index_university_files', methods: ['GET'])]
    public function indexUniversityFiles(FileRepository $fileRepository, SerializerInterface $serializer, $id)
    {
        try {
            $files = $fileRepository->createQueryBuilder('f')
                ->join('f.subject', 's')
                ->join('s.degrees', 'd')
                ->join('d.university', 'u')
                ->join('f.user', 'us')
                ->where('u.id = :id')
                ->setParameter('id', $id)
                ->select('f.id', 'f.name', 'f.category', 'f.type', 'f.extra', 'f.url', 'us.email as user')
                ->getQuery()
                ->getResult();
        } catch (\Exception $e) {
            return new Response(json_encode(['message' => $e->getMessage()]), 412, ['Content-Type' => 'application/json']);
        }

        $response = $serializer->serialize($files, 'json');

        return new Response($response, 200, [
            'Content-Type' => 'application/json'
        ]);

    }

    #[Route('/api/files/degree/{id}', name: 'index_degree_files', methods: ['GET'])]
    public function indexDegreeFiles(FileRepository $fileRepository, SerializerInterface $serializer, $id)
    {
        try {
            $files = $fileRepository->createQueryBuilder('f')
                ->join('f.subject', 's')
                ->join('s.degrees', 'd')
                ->join('f.user', 'us')
                ->where('d.id = :id')
                ->setParameter('id', $id)
                ->select('f.id', 'f.name', 'f.category', 'f.type', 'f.extra', 'f.url', 'us.email as user')
                ->getQuery()
                ->getResult();
        } catch (\Exception $e) {
            return new Response(json_encode(['message' => $e->getMessage()]), 412, ['Content-Type' => 'application/json']);
        }

        $response = $serializer->serialize($files, 'json');

        return new Response($response, 200, [
            'Content-Type' => 'application/json'
        ]);

    }

    #[Route('/api/files/subject/{id}', name: 'index_subject_files', methods: ['GET'])]
    public function indexSubjectFiles(FileRepository $fileRepository, SerializerInterface $serializer, $id)
    {
        try {
            $files = $fileRepository->createQueryBuilder('f')
                ->join('f.subject', 's')
                ->join('f.user', 'us')
                ->where('s.id = :id')
                ->setParameter('id', $id)
                ->select('f.id', 'f.name', 'f.category', 'f.type', 'f.extra', 'f.url', 'us.email as user')
                ->getQuery()
                ->getResult();
        } catch (\Exception $e) {
            return new Response(json_encode(['message' => $e->getMessage()]), 412, ['Content-Type' => 'application/json']);
        }

        $response = $serializer->serialize($files, 'json');

        return new Response($response, 200, [
            'Content-Type' => 'application/json'
        ]);

    }

    #[Route('/api/files/{id}', name: 'index_file', methods: ['GET'])]
    public function indexFile(FileRepository $fileRepository, SerializerInterface $serializer, $id)
    {
        try {
            $file = $fileRepository->createQueryBuilder('f')
                ->join('f.user', 'u')
                ->leftJoin('f.ratings', 'r')
                ->where('f.id = :id')
                ->setParameter('id', $id)
                ->select('f.id', 'f.name', 'f.category', 'f.type', 'f.extra', 'f.url', 'u.sub as user', 'AVG(r.value) as rating')
                ->getQuery()
                ->getSingleResult();
        } catch (\Exception $e) {
            return new Response(json_encode(['message' => $e->getMessage()]), 412, ['Content-Type' => 'application/json']);
        }

        $response = $serializer->serialize($file, 'json');

        return new Response($response, 200, [
            'Content-Type' => 'application/json'
        ]);

    }


    #[Route('/api/files', name: 'index_files', methods: ['GET'])]
    public function indexFiles(FileRepository $fileRepository, SerializerInterface $serializer)
    {

        $files = $fileRepository->createQueryBuilder('f')
            ->join('f.subject', 's')
            ->join('f.user', 'u')
            ->leftJoin('f.ratings', 'r')
            ->select('f.id, f.name, f.category, f.type, f.extra, f.url, s.name as subject, u.email as user, COALESCE(AVG(r.value), 0) as rating')
            ->groupBy('f.id')
            ->getQuery()
            ->getResult();

        $response = $serializer->serialize($files, 'json');

        return new Response($response, 200, [
            'Content-Type' => 'application/json'
        ]);

    }

}
