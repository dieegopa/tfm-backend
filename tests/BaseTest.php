<?php

namespace App\Tests;

use App\Controller\CourseController;
use App\Controller\DegreeController;
use App\Entity\Course;
use App\Entity\Degree;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class BaseTest extends KernelTestCase
{
    use ResetDatabase, Factories;

    protected $courseController = null;
    protected $courseRepository = null;
    protected $degreeController = null;
    protected $degreeRepository = null;
    protected $userRepository = null;
    protected $entityManager = null;
    protected $serializer = null;
    protected $managerRegistry = null;

    protected function setUp(): void
    {
        self::bootKernel();

        /** Controllers **/
        $this->courseController = new CourseController();
        $this->degreeController = new DegreeController();

        /** Entity Manager **/
        $this->entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        /** Repositories **/
        $this->courseRepository = $this->entityManager->getRepository(Course::class);
        $this->degreeRepository = $this->entityManager->getRepository(Degree::class);
        $this->userRepository = $this->entityManager->getRepository(User::class);

        /** Serializer **/
        $this->serializer = static::$kernel->getContainer()->get('jms_serializer');

        /** Manager Registry **/
        $this->managerRegistry = static::$kernel->getContainer()->get('doctrine');

        parent::setUp();
    }

}