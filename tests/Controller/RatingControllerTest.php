<?php

namespace App\Tests\Controller;

use App\Factory\FileFactory;
use App\Factory\RatingFactory;
use App\Factory\SubjectFactory;
use App\Factory\UserFactory;
use App\Tests\BaseTest;
use Symfony\Component\HttpFoundation\Request;
use function Zenstruck\Foundry\faker;

class RatingControllerTest extends BaseTest
{

    public function testSaveRating()
    {
        $user = UserFactory::createOne();
        $subject = SubjectFactory::createOne();
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
          '/api/ratings',
            'PATCH',
            [],
            [],
            [],
            [],
            json_encode([
                'file_id' => $file->getId(),
                'user_sub' => $user->getSub(),
                'value' => 5,
            ])
        );

        $response = $this->ratingController->saveRating($this->managerRegistry, $this->fileRepository, $this->ratingRepository, $this->userRepository, $request, $this->serializer);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals(5, json_decode($response->getContent())->rating);
    }

    public function testSaveRatingBadRequest()
    {
        $user = UserFactory::createOne();
        $subject = SubjectFactory::createOne();
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
            '/api/ratings',
            'PATCH',
            [],
            [],
            [],
            [],
            json_encode([
                'file' => $file->getId(),
                'user_sub' => $user->getSub(),
                'value' => 5,
            ])
        );

        $response = $this->ratingController->saveRating($this->managerRegistry, $this->fileRepository, $this->ratingRepository, $this->userRepository, $request, $this->serializer);
        $this->assertEquals(412, $response->getStatusCode());
    }

    public function testSaveRatingNotFound()
    {
        $user = UserFactory::createOne();
        $subject = SubjectFactory::createOne();
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
            '/api/ratings',
            'PATCH',
            [],
            [],
            [],
            [],
            json_encode([
                'file_id' => $file->getId(),
                'user_sub' => faker()->uuid(),
                'value' => 5,
            ])
        );

        $response = $this->ratingController->saveRating($this->managerRegistry, $this->fileRepository, $this->ratingRepository, $this->userRepository, $request, $this->serializer);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testSaveRatingUpdate()
    {
        $user = UserFactory::createOne();
        $subject = SubjectFactory::createOne();
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
            '/api/ratings',
            'PATCH',
            [],
            [],
            [],
            [],
            json_encode([
                'file_id' => $file->getId(),
                'user_sub' => $user->getSub(),
                'value' => 5,
            ])
        );

        $this->ratingController->saveRating($this->managerRegistry, $this->fileRepository, $this->ratingRepository, $this->userRepository, $request, $this->serializer);
        $response = $this->ratingController->saveRating($this->managerRegistry, $this->fileRepository, $this->ratingRepository, $this->userRepository, $request, $this->serializer);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals(5, json_decode($response->getContent())->rating);
    }

    public function testIndexFileRating()
    {
        $user = UserFactory::createOne();
        $subject = SubjectFactory::createOne();
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
        $rating = RatingFactory::createOne([
            'file' => $file,
            'user' => $user,
            'value' => 5,
        ]);

        $response = $this->ratingController->indexFileRating($this->ratingRepository, $this->serializer, $file->getId(), $user->getSub());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals(5, json_decode($response->getContent())->rating);
    }

    public function testIndexFileRatingNotFound()
    {
        $response = $this->ratingController->indexFileRating($this->ratingRepository, $this->serializer, faker()->uuid(), faker()->uuid());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals(0, json_decode($response->getContent())->rating);
    }


    public function testOptionsRatings()
    {
        $response = $this->ratingController->optionsRatings();
        $this->assertEquals(204, $response->getStatusCode());

        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('PATCH, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type, Authorization', $response->headers->get('Access-Control-Allow-Headers'));
    }

    public function testOptionsRatingsFile()
    {
        $response = $this->ratingController->optionsRatingsFile();
        $this->assertEquals(204, $response->getStatusCode());

        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('GET, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type, Authorization', $response->headers->get('Access-Control-Allow-Headers'));
    }

}