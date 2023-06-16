<?php

namespace App\Tests\Controller;

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

    public static function setUpBeforeClass(): void
    {

        static::bootKernel();

        self::$client = new Client([
            'base_uri' => 'https://127.0.0.1:8080',
            'defaults' => [
                'exceptions' => false
            ]
        ]);

        parent::setUpBeforeClass();

    }

    public function testIndexDegrees()
    {
        $degree = DegreeFactory::createOne([
            'name' => 'Degree 1',
            'slug' => 'degree1',
        ]);

        $response = self::$client->get('/free/degrees');

        $responseBody = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($degree->getName(), $responseBody[0]['name']);
        $this->assertEquals($degree->getSlug(), $responseBody[0]['slug']);

    }

    public function testIndexDegreeUniveristy()
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

        $response = self::$client->get('/free/degrees/' . $university->getSlug() . '/' . $degree->getSlug());

        $responseBody = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($degree->getName(), $responseBody[0]['name']);
        $this->assertEquals($degree->getSlug(), $responseBody[0]['slug']);
        $this->assertEquals($degree->getUniversity()->getName(), $responseBody[0]['university']['name']);

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

        $response = self::$client->patch('/api/degrees/favorite', [
            'json' => [
                'user_sub' => $user->getSub(),
                'degree_id' => $degree->getId(),
            ]
        ]);

        $responseBody = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $responseBody['favorite']);


    }

}