<?php

namespace App\Tests\Controller;

use App\Factory\DegreeFactory;
use App\Factory\FileFactory;
use App\Factory\SubjectFactory;
use App\Factory\UniversityFactory;
use App\Factory\UserFactory;
use App\Tests\BaseTest;
use Symfony\Component\HttpFoundation\Request;
use function Zenstruck\Foundry\faker;

class FileControllerTest extends BaseTest
{
    public function testUploadFile()
    {
        $user = UserFactory::createOne([
            'email' => 'test@test.com',
            'sub' => 'test',
        ]);

        $subject = SubjectFactory::createOne([
            'name' => 'Subject 1',
            'slug' => 'subject1',
        ]);

        $request = Request::create(
            '/api/files/upload',
            'POST',
            [
                'fileName' => 'test.pdf',
                'uniqueName' => faker()->name(),
                'fileCategory' => 'notes',
                'fileExtra' => faker()->text(),
                'fileType' => 'application/pdf',
                'subjectId' => $subject->getId(),
                'userSub' => $user->getSub(),
                'fileUrl' => faker()->url(),
            ],
            [],
            [],
            [],
        );

        $response = $this->fileController->uploadFile($this->managerRegistry, $this->subjectRepository, $this->userRepository, $request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Uploaded', json_decode($response->getContent(), true)['message'][0]);

    }

    public function testUploadFileNotFound()
    {

        $request = Request::create(
            '/api/files/upload',
            'POST',
            [
                'fileName' => 'test.pdf',
                'uniqueName' => faker()->name(),
                'fileCategory' => 'notes',
                'fileExtra' => faker()->text(),
                'fileType' => 'application/pdf',
                'subjectId' => 'someId',
                'userSub' => 'someSub',
                'fileUrl' => faker()->url(),
            ],
            [],
            [],
            [],
        );

        $response = $this->fileController->uploadFile($this->managerRegistry, $this->subjectRepository, $this->userRepository, $request);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Not Found', json_decode($response->getContent(), true)['message']);

    }

    public function testIndexUserFiles()
    {
        $user = UserFactory::createOne([
            'email' => 'test@test.com',
            'sub' => 'test',
        ]);

        $subject = SubjectFactory::createOne([
            'name' => 'Subject 1',
            'slug' => 'subject1',
        ]);

        $file = FileFactory::createOne([
            'name' => faker()->name(),
            'uniqueName' => faker()->name(),
            'category' => 'notes',
            'extra' => faker()->text(),
            'type' => 'application/pdf',
            'subject' => $subject,
            'user' => $user,
            'url' => faker()->url(),
        ]);

        $response = $this->fileController->indexUserFiles($this->fileRepository, $this->serializer, $user->getSub());
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($file->getName(), $data[0]['name']);
    }

    public function testIndexUniversityFiles()
    {
        $user = UserFactory::createOne([
            'email' => 'test@test.com',
            'sub' => 'test',
        ]);

        $subject = SubjectFactory::createOne([
            'name' => 'Subject 1',
            'slug' => 'subject1',
        ]);

        $university = UniversityFactory::createOne([
            'name' => 'University 1',
            'slug' => 'university1',
        ]);

        $degree = DegreeFactory::createOne([
            'name' => 'Degree 1',
            'slug' => 'degree1',
            'subject' => [$subject],
            'university' => $university,
        ]);

        $file = FileFactory::createOne([
            'name' => faker()->name(),
            'uniqueName' => faker()->name(),
            'category' => 'notes',
            'extra' => faker()->text(),
            'type' => 'application/pdf',
            'subject' => $subject,
            'user' => $user,
            'url' => faker()->url(),
        ]);

        $response = $this->fileController->indexUniversityFiles($this->fileRepository, $this->serializer, $university->getId());
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($file->getName(), $data[0]['name']);
        $this->assertEquals($user->getEmail(), $data[0]['user']);

    }

    public function testIndexDegreeFiles()
    {
        $user = UserFactory::createOne([
            'email' => 'test@test.com',
            'sub' => 'test',
        ]);

        $subject = SubjectFactory::createOne([
            'name' => 'Subject 1',
            'slug' => 'subject1',
        ]);

        $university = UniversityFactory::createOne([
            'name' => 'University 1',
            'slug' => 'university1',
        ]);

        $degree = DegreeFactory::createOne([
            'name' => 'Degree 1',
            'slug' => 'degree1',
            'subject' => [$subject],
            'university' => $university,
        ]);

        $file = FileFactory::createOne([
            'name' => faker()->name(),
            'uniqueName' => faker()->name(),
            'category' => 'notes',
            'extra' => faker()->text(),
            'type' => 'application/pdf',
            'subject' => $subject,
            'user' => $user,
            'url' => faker()->url(),
        ]);

        $response = $this->fileController->indexDegreeFiles($this->fileRepository, $this->serializer, $degree->getId());

        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($file->getName(), $data[0]['name']);
        $this->assertEquals($user->getEmail(), $data[0]['user']);
    }

    public function testIndexSubjectFiles()
    {
        $user = UserFactory::createOne([
            'email' => 'test@test.com',
            'sub' => 'test',
        ]);

        $subject = SubjectFactory::createOne([
            'name' => 'Subject 1',
            'slug' => 'subject1',
        ]);

        $file = FileFactory::createOne([
            'name' => faker()->name(),
            'uniqueName' => faker()->name(),
            'category' => 'notes',
            'extra' => faker()->text(),
            'type' => 'application/pdf',
            'subject' => $subject,
            'user' => $user,
            'url' => faker()->url(),
        ]);

        $response = $this->fileController->indexSubjectFiles($this->fileRepository, $this->serializer, $subject->getId());

        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($file->getName(), $data[0]['name']);
        $this->assertEquals($user->getEmail(), $data[0]['user']);
    }

    public function testIndexFile()
    {
        $user = UserFactory::createOne([
            'email' => 'test@test.com',
            'sub' => 'test',
        ]);

        $subject = SubjectFactory::createOne([
            'name' => 'Subject 1',
            'slug' => 'subject1',
        ]);

        $file = FileFactory::createOne([
            'name' => faker()->name(),
            'uniqueName' => faker()->name(),
            'category' => 'notes',
            'extra' => faker()->text(),
            'type' => 'application/pdf',
            'subject' => $subject,
            'user' => $user,
            'url' => faker()->url(),
        ]);

        $response = $this->fileController->indexFile($this->fileRepository, $this->serializer, $file->getId());

        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($file->getName(), $data['name']);
        $this->assertEquals($user->getSub(), $data['user']);
    }

    public function testIndexFiles()
    {
        $user = UserFactory::createOne([
            'email' => 'test@test.com',
            'sub' => 'test',
        ]);

        $subject = SubjectFactory::createOne([
            'name' => 'Subject 1',
            'slug' => 'subject1',
        ]);

        $file = FileFactory::createOne([
            'name' => faker()->name(),
            'uniqueName' => faker()->name(),
            'category' => 'notes',
            'extra' => faker()->text(),
            'type' => 'application/pdf',
            'subject' => $subject,
            'user' => $user,
            'url' => faker()->url(),
        ]);

        $response = $this->fileController->indexFiles($this->fileRepository, $this->serializer);

        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($file->getName(), $data[0]['name']);
        $this->assertEquals($user->getEmail(), $data[0]['user']);
    }

    public function testDeleteFile()
    {
        $user = UserFactory::createOne([
            'email' => 'test@test.com',
            'sub' => 'test',
        ]);

        $subject = SubjectFactory::createOne([
            'name' => 'Subject 1',
            'slug' => 'subject1',
        ]);

        $file = FileFactory::createOne([
            'name' => faker()->name(),
            'uniqueName' => faker()->name(),
            'category' => 'notes',
            'extra' => faker()->text(),
            'type' => 'application/pdf',
            'subject' => $subject,
            'user' => $user,
            'url' => faker()->url(),
        ]);

        $response = $this->fileController->deleteFile($this->managerRegistry, $this->fileRepository, $this->serializer, $file->getId());

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteFileNotFound()
    {
        $response = $this->fileController->deleteFile($this->managerRegistry, $this->fileRepository, $this->serializer, 1);

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testUpdateFile()
    {
        $user = UserFactory::createOne([
            'email' => 'test@test.com',
            'sub' => 'test',
        ]);

        $subject = SubjectFactory::createOne([
            'name' => 'Subject 1',
            'slug' => 'subject1',
        ]);

        $file = FileFactory::createOne([
            'name' => faker()->name(),
            'uniqueName' => faker()->name(),
            'category' => 'notes',
            'extra' => faker()->text(),
            'type' => 'application/pdf',
            'subject' => $subject,
            'user' => $user,
            'url' => faker()->url(),
        ]);

        $request = Request::create(
            '/api/files/' . $file->getId(),
            'PUT',
            [],
            [],
            [],
            [],
            json_encode([
                'fileName' => 'New name',
                'category' => 'new category',
                'fileExtra' => 'new extra',
            ])
        );

        $response = $this->fileController->updateFile($this->managerRegistry, $this->fileRepository, $request, $file->getId());

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUpdateFileNotFound()
    {
        $request = Request::create(
            '/api/files/1',
            'PUT',
            [],
            [],
            [],
            [],
            json_encode([
                'fileName' => 'New name',
                'category' => 'new category',
                'fileExtra' => 'new extra',
            ])
        );

        $response = $this->fileController->updateFile($this->managerRegistry, $this->fileRepository, $request, 1);

        $this->assertEquals(404, $response->getStatusCode());
    }


    public function testOptionsFilesUpload()
    {
        $response = $this->fileController->optionsFilesUpload();

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('POST, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type, Authorization', $response->headers->get('Access-Control-Allow-Headers'));
    }

    public function testOptionsFilesUser()
    {
        $response = $this->fileController->optionsFilesUser();

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('GET, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type, Authorization', $response->headers->get('Access-Control-Allow-Headers'));
    }

    public function testOptionsFilesUniversity()
    {
        $response = $this->fileController->optionsFilesUniversity();

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('GET, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type, Authorization', $response->headers->get('Access-Control-Allow-Headers'));
    }

    public function testOptionsFilesDegree()
    {
        $response = $this->fileController->optionsFilesDegree();

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('GET, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type, Authorization', $response->headers->get('Access-Control-Allow-Headers'));
    }

    public function testOptionsFilesSubject()
    {
        $response = $this->fileController->optionsFilesSubject();

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('GET, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type, Authorization', $response->headers->get('Access-Control-Allow-Headers'));
    }

    public function testOptionsFilesId()
    {
        $response = $this->fileController->optionsFilesId();

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('GET, PUT, DELETE, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type, Authorization', $response->headers->get('Access-Control-Allow-Headers'));
    }

    public function testOptionsFilesAll()
    {
        $response = $this->fileController->optionsFilesAll();

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('GET, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type, Authorization', $response->headers->get('Access-Control-Allow-Headers'));
    }
}