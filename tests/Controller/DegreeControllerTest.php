<?php

namespace App\Tests\Controller;

use App\Controller\DegreeController;
use App\Entity\Degree;
use App\Factory\DegreeFactory;
use App\Factory\UniversityFactory;
use App\Factory\UserFactory;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class DegreeControllerTest extends KernelTestCase
{

    use ResetDatabase, Factories;

    private static Client $client;


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

        $degreeController = new DegreeController();
        $entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $degreeRepository = $entityManager->getRepository(Degree::class);
        $serializer = static::$kernel->getContainer()->get('jms_serializer');

        $response = $degreeController->indexDegree($degreeRepository, $serializer, 'university1', 'degree1');
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($degree->getName(), $data[0]['name']);
        $this->assertEquals($degree->getSlug(), $data[0]['slug']);
        $this->assertEquals($degree->getUniversity()->getName(), $data[0]['university']['name']);

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