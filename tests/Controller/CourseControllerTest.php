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

    protected function setUp(): void
    {
        self::bootKernel();
        parent::setUp();
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

    public function testIndexCourseDegreesNotFound()
    {

        $courseController = new CourseController();
        $entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $courseRepository = $entityManager->getRepository(Course::class);
        $serializer = static::$kernel->getContainer()->get('jms_serializer');

        $response = $courseController->indexCourseDegrees($courseRepository, $serializer, 'degree1');

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Not Found', json_decode($response->getContent(), true)['message']);

    }

    public function testOptions()
    {
        $courseController = new CourseController();
        $response = $courseController->optionsCourses();

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('GET, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type', $response->headers->get('Access-Control-Allow-Headers'));

    }

}