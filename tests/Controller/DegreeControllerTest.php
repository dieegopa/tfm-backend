<?php

namespace App\Tests\Controller;

use App\Factory\DegreeFactory;
use App\Factory\UniversityFactory;
use App\Factory\UserFactory;
use App\Tests\BaseTest;
use Symfony\Component\HttpFoundation\Request;

class DegreeControllerTest extends BaseTest
{

    public function testIndexDegrees()
    {

        $university = UniversityFactory::createOne([
            'name' => 'University 1',
            'slug' => 'university1',
        ]);

        $degree = DegreeFactory::createOne([
            'name' => 'Degree 1',
            'slug' => 'degree1',
            'university' => $university,
        ]);

        $response = $this->degreeController->indexDegree($this->degreeRepository, $this->serializer, 'university1', 'degree1');
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($degree->getName(), $data[0]['name']);
        $this->assertEquals($degree->getSlug(), $data[0]['slug']);
        $this->assertEquals($degree->getUniversity()->getName(), $data[0]['university']['name']);

    }


    public function testFavoriteDegree()
    {

        $user = UserFactory::createOne([
            'email' => 'test@test.com',
            'sub' => 'test',
        ]);

        $degree = DegreeFactory::createOne([
            'name' => 'Degree 1',
            'slug' => 'degree1',
        ]);

        $request = Request::create(
            '/api/degrees/favorite',
            'PATCH',
            [],
            [],
            [],
            [],
            json_encode([
                'user_sub' => $user->getSub(),
                'degree_id' => $degree->getId(),
            ]),
        );

        $response = $this->degreeController->favoriteDegree($this->managerRegistry, $this->degreeRepository, $this->userRepository, $request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, json_decode($response->getContent(), true)['favorite']);

    }

    public function testOptionsDegrees()
    {
        $response = $this->degreeController->optionsDegrees();

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('GET, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type', $response->headers->get('Access-Control-Allow-Headers'));

    }

    public function testOptionsDegreesUniversity()
    {
        $response = $this->degreeController->optionsDegreesUniversity();

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('GET, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type', $response->headers->get('Access-Control-Allow-Headers'));

    }

    public function testOptionsDegreesFavorite()
    {
        $response = $this->degreeController->optionsDegreesFavorite();

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('PATCH, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type, Authorization', $response->headers->get('Access-Control-Allow-Headers'));

    }

//    public function testFavoriteDegree()
//    {
//
//        $user = UserFactory::createOne([
//            'email' => 'test@test.com',
//            'sub' => 'test',
//        ]);
//
//        $degree = DegreeFactory::createOne([
//            'name' => 'Degree 1',
//            'slug' => 'degree1',
//        ]);
//
//        $response = self::$client->patch('/api/degrees/favorite', [
//            'json' => [
//                'user_sub' => $user->getSub(),
//                'degree_id' => $degree->getId(),
//            ]
//        ]);
//
//        $responseBody = json_decode($response->getBody(), true);
//
//        $this->assertEquals(200, $response->getStatusCode());
//        $this->assertEquals(true, $responseBody['favorite']);
//
//
//    }

}