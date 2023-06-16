<?php

namespace App\Tests\Controller;

use App\Factory\CourseFactory;
use App\Factory\DegreeFactory;
use App\Tests\BaseTest;

class CourseControllerTest extends BaseTest
{

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

        $response = $this->courseController->indexCourseDegrees($this->courseRepository, $this->serializer, 'degree1');
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($course->getName(), $data[0]['name']);
        $this->assertEquals($course->getSlug(), $data[0]['slug']);
        $this->assertEquals($course->getNumber(), $data[0]['number']);

    }

    public function testIndexCourseDegreesNotFound()
    {

        $response = $this->courseController->indexCourseDegrees($this->courseRepository, $this->serializer, 'degree1');

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Not Found', json_decode($response->getContent(), true)['message']);

    }

    public function testOptions()
    {
        $response = $this->courseController->optionsCourses();

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('GET, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type', $response->headers->get('Access-Control-Allow-Headers'));

    }

}