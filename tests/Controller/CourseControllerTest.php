<?php

namespace App\Tests\Controller;

use App\Controller\CourseController;
use App\Entity\Course;
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
            'base_uri' => 'https://127.0.0.1:8080',
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

        $courseController = new CourseController();

        $entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $courseRepository = $entityManager->getRepository(Course::class);
        $serializer = static::$kernel->getContainer()->get('jms_serializer');

        $response = $courseController->indexCourseDegrees($courseRepository, $serializer, 'degree1');
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($course->getName(), $data[0]['name']);
        $this->assertEquals($course->getSlug(), $data[0]['slug']);
        $this->assertEquals($course->getNumber(), $data[0]['number']);

    }

}