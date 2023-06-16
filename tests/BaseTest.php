<?php

namespace App\Tests;

use App\Controller\CourseController;
use App\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class BaseTest extends KernelTestCase
{
    use ResetDatabase, Factories;

    protected $courseController = null;
    protected $courseRepository = null;
    protected $entityManager = null;
    protected $serializer = null;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->courseController = new CourseController();
        $this->entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->courseRepository = $this->entityManager->getRepository(Course::class);
        $this->serializer = static::$kernel->getContainer()->get('jms_serializer');

        parent::setUp();
    }

}