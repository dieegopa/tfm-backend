<?php

namespace App\Tests\Controller;

use App\Factory\SubjectFactory;
use App\Factory\UserFactory;
use App\Tests\BaseTest;
use Symfony\Component\HttpFoundation\Request;

class SubjectControllerTest extends BaseTest
{

    public function testIndexSubjects()
    {
        $subject = SubjectFactory::createOne();
        $response = $this->subjectController->indexSubjects($this->subjectRepository, $this->serializer, $subject->getSlug());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals($subject->getSlug(), json_decode($response->getContent())->slug);
    }

    public function testIndexSubjectsNotFound()
    {
        $response = $this->subjectController->indexSubjects($this->subjectRepository, $this->serializer, 'not-found');
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFavoriteSubject()
    {
        $subject = SubjectFactory::createOne();
        $user = UserFactory::createOne();

        $request = Request::create(
            '/api/subjects/favorite',
            'PATCH',
            [],
            [],
            [],
            [],
            json_encode([
                'subject_id' => $subject->getId(),
                'user_sub' => $user->getSub(),
            ])
        );

        $response = $this->subjectController->favoriteSubject($this->managerRegistry, $this->subjectRepository, $this->userRepository, $request);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals(true, json_decode($response->getContent())->favorite);
    }

    public function testFavoriteSubjectBadRequest()
    {
        $subject = SubjectFactory::createOne();
        $user = UserFactory::createOne();

        $request = Request::create(
            '/api/subjects/favorite',
            'PATCH',
            [],
            [],
            [],
            [],
            json_encode([
                'subject' => $subject->getId(),
                'user_sub' => $user->getSub(),
            ])
        );

        $response = $this->subjectController->favoriteSubject($this->managerRegistry, $this->subjectRepository, $this->userRepository, $request);
        $this->assertEquals(412, $response->getStatusCode());
    }

    public function testFavoriteSubjectNotFound()
    {
        $user = UserFactory::createOne();

        $request = Request::create(
            '/api/subjects/favorite',
            'PATCH',
            [],
            [],
            [],
            [],
            json_encode([
                'subject_id' => 'not-found',
                'user_sub' => $user->getSub(),
            ])
        );

        $response = $this->subjectController->favoriteSubject($this->managerRegistry, $this->subjectRepository, $this->userRepository, $request);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFavoriteUnfavoriteSubject()
    {
        $subject = SubjectFactory::createOne();
        $user = UserFactory::createOne();

        $request = Request::create(
            '/api/subjects/favorite',
            'PATCH',
            [],
            [],
            [],
            [],
            json_encode([
                'subject_id' => $subject->getId(),
                'user_sub' => $user->getSub(),
            ])
        );

        $this->subjectController->favoriteSubject($this->managerRegistry, $this->subjectRepository, $this->userRepository, $request);
        $response = $this->subjectController->favoriteSubject($this->managerRegistry, $this->subjectRepository, $this->userRepository, $request);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals(false, json_decode($response->getContent())->favorite);
    }


    public function testOptionsSubjectsSubject()
    {
        $response = $this->subjectController->optionsSubjectsSubject();
        $this->assertEquals(204, $response->getStatusCode());

        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('GET, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type, Authorization', $response->headers->get('Access-Control-Allow-Headers'));
    }

    public function testOptionsSubjectsFavorite()
    {
        $response = $this->subjectController->optionsSubjectsFavorite();
        $this->assertEquals(204, $response->getStatusCode());

        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('PATCH, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type, Authorization', $response->headers->get('Access-Control-Allow-Headers'));
    }

}