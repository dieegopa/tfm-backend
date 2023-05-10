<?php

namespace App\Controller;

use App\Entity\File;
use App\Repository\SubjectRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileController extends AbstractController
{
    #[Route('/file', name: 'app_file')]
    public function index(): Response
    {
        return $this->render('file/index.html.twig', [
            'controller_name' => 'FileController',
        ]);
    }


    #[Route('/api/files/upload', name: 'upload_files', methods: ['POST'])]
    public function uploadFile(ManagerRegistry $doctrine, SubjectRepository $subjectRepository, UserRepository $userRepository, Request $request, SluggerInterface $slugger)
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
}
