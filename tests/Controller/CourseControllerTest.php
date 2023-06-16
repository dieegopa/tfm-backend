<?php

namespace App\Tests\Controller;

use App\Factory\CourseFactory;
use App\Factory\DegreeFactory;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class CourseControllerTest extends KernelTestCase
{
    use ResetDatabase, Factories;

    private static Client $client;

    public static function setUpBeforeClass(): void
    {

        static::bootKernel();

        self::$client = new Client([
            'base_uri' => 'https://localhost:8000',
            'defaults' => [
                'exceptions' => false
            ]
        ]);

        parent::setUpBeforeClass();

    }

    public function testIndexCourseDegrees()
    {
        $degree = DegreeFactory::createOne([
            'name' => 'Degree 1',
            'slug' => 'degree1',
        ]);

        $course = CourseFactory::createOne([
            'name' => 'Course 1',
            'slug' => 'course1',
            'number' => 1,
            'degree' => $degree,
        ]);

        $response = self::$client->get('/free/courses/' . $degree->getSlug());

        $responseBody = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($course->getName(), $responseBody[0]['name']);
        $this->assertEquals($course->getSlug(), $responseBody[0]['slug']);
        $this->assertEquals($course->getNumber(), $responseBody[0]['number']);
        $this->assertEquals($course->getDegree()->getName(), $responseBody[0]['degree']['name']);

    }

}