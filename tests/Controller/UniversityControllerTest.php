<?php

namespace App\Tests\Controller;

use App\Factory\UniversityFactory;
use App\Factory\UserFactory;
use App\Tests\BaseTest;
use Symfony\Component\HttpFoundation\Request;

class UniversityControllerTest extends BaseTest
{
    public function testIndex()
    {
        $university = UniversityFactory::createOne();

        $request = Request::create(
            '/free/universities',
            'GET',
            [],
            [],
            [],
            [],
            null
        );

        $response = $this->universityController->index($this->universityRepository, $this->serializer, $request);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals($university->getSlug(), json_decode($response->getContent())[0]->slug);

    }

    public function testIndexWithQuery()
    {
        $university = UniversityFactory::createOne();

        $request = Request::create(
            '/free/universities?search=' . $university->getName(),
            'GET',
            [],
            [],
            [],
            [],
            null
        );

        $response = $this->universityController->index($this->universityRepository, $this->serializer, $request);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals($university->getName(), json_decode($response->getContent())[0]->name);

    }

    public function testIndexUniversity()
    {
        $university = UniversityFactory::createOne();

        $response = $this->universityController->indexUniversity($this->universityRepository, $this->serializer, $university->getSlug());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals($university->getSlug(), json_decode($response->getContent())->slug);
    }

    public function testIndexUniversityNotFound()
    {
        $response = $this->universityController->indexUniversity($this->universityRepository, $this->serializer, 'not-found');
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFavoriteUniversity()
    {
        $university = UniversityFactory::createOne();
        $user = UserFactory::createOne();

        $request = Request::create(
            '/api/universities/favorite',
            'PATCH',
            [],
            [],
            [],
            [],
            json_encode([
                'university_id' => $university->getId(),
                'user_sub' => $user->getSub(),
            ])
        );

        $response = $this->universityController->favoriteUniversity($this->managerRegistry, $this->universityRepository, $this->userRepository, $request);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals(true, json_decode($response->getContent())->favorite);
    }

    public function testFavoriteUniversityBadRequest()
    {
        $university = UniversityFactory::createOne();
        $user = UserFactory::createOne();

        $request = Request::create(
            '/api/universities/favorite',
            'PATCH',
            [],
            [],
            [],
            [],
            json_encode([
                'university' => $university->getId(),
                'user_sub' => $user->getSub(),
            ])
        );

        $response = $this->universityController->favoriteUniversity($this->managerRegistry, $this->universityRepository, $this->userRepository, $request);
        $this->assertEquals(412, $response->getStatusCode());
    }

    public function testFavoriteUniversityNotFound()
    {
        $user = UserFactory::createOne();

        $request = Request::create(
            '/api/universities/favorite',
            'PATCH',
            [],
            [],
            [],
            [],
            json_encode([
                'university_id' => 0,
                'user_sub' => $user->getSub(),
            ])
        );

        $response = $this->universityController->favoriteUniversity($this->managerRegistry, $this->universityRepository, $this->userRepository, $request);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testFavoriteUnfavoriteUniversity()
    {
        $university = UniversityFactory::createOne();
        $user = UserFactory::createOne();

        $request = Request::create(
            '/api/universities/favorite',
            'PATCH',
            [],
            [],
            [],
            [],
            json_encode([
                'university_id' => $university->getId(),
                'user_sub' => $user->getSub(),
            ])
        );

        $response = $this->universityController->favoriteUniversity($this->managerRegistry, $this->universityRepository, $this->userRepository, $request);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals(true, json_decode($response->getContent())->favorite);

        $response = $this->universityController->favoriteUniversity($this->managerRegistry, $this->universityRepository, $this->userRepository, $request);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals(false, json_decode($response->getContent())->favorite);
    }

    public function testIsFavoriteUniversity()
    {
        $university = UniversityFactory::createOne();
        $user = UserFactory::createOne([
            'universities' => [$university],
        ]);

        $response = $this->universityController->isFavoriteUniversity($this->universityRepository, $university->getId(), $user->getSub());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals(true, json_decode($response->getContent())->favorite);

    }

    public function testIsFavoriteUniversityFalse()
    {
        $university = UniversityFactory::createOne();
        $user = UserFactory::createOne();

        $response = $this->universityController->isFavoriteUniversity($this->universityRepository, $university->getId(), $user->getSub());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals(false, json_decode($response->getContent())->favorite);

    }

    public function testIsFavoriteUniversityNotFound()
    {
        $university = UniversityFactory::createOne();
        $user = UserFactory::createOne([
            'universities' => [$university],
        ]);

        $response = $this->universityController->isFavoriteUniversity($this->universityRepository, 0, $user->getSub());
        $this->assertEquals(404, $response->getStatusCode());

    }

    public function testOptionsUniversitiesFree()
    {
        $response = $this->universityController->optionsUniversitiesFree();
        $this->assertEquals(204, $response->getStatusCode());

        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('GET, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type, Authorization', $response->headers->get('Access-Control-Allow-Headers'));
    }

    public function testOptionsUniversitiesFreeSlug()
    {
        $response = $this->universityController->optionsUniversitiesFreeSlug();
        $this->assertEquals(204, $response->getStatusCode());

        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('GET, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type, Authorization', $response->headers->get('Access-Control-Allow-Headers'));
    }

    public function testOptionsUniversitiesFavorite()
    {
        $response = $this->universityController->optionsUniversitiesFavorite();
        $this->assertEquals(204, $response->getStatusCode());

        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('PATCH, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type, Authorization', $response->headers->get('Access-Control-Allow-Headers'));
    }

    public function testOptionsUniversitiesUniUser()
    {
        $response = $this->universityController->optionsUniversitiesUniUser();
        $this->assertEquals(204, $response->getStatusCode());

        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('GET, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type, Authorization', $response->headers->get('Access-Control-Allow-Headers'));
    }

}