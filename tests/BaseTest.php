<?php

namespace App\Tests;

use App\Controller\CourseController;
use App\Controller\DegreeController;
use App\Controller\FileController;
use App\Controller\RatingController;
use App\Controller\RegisterController;
use App\Controller\UniversityController;
use App\Controller\UserController;
use App\Entity\Course;
use App\Entity\Degree;
use App\Entity\File;
use App\Entity\Rating;
use App\Entity\University;
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
    protected $userController = null;
    protected $userRepository = null;
    protected $fileController = null;
    protected $fileRepository = null;
    protected $universityController = null;
    protected $universityRepository = null;
    protected $ratingController = null;
    protected $ratingRepository = null;
    protected $registerController = null;
    protected $registerRepository = null;
    protected $subjectController = null;
    protected $subjectRepository = null;
    protected $entityManager = null;
    protected $serializer = null;
    protected $managerRegistry = null;

    protected function setUp(): void
    {
        self::bootKernel();

        /** Controllers **/
        $this->courseController = new CourseController();
        $this->degreeController = new DegreeController();
        $this->userController = new UserController();
        $this->fileController = new FileController();
        $this->universityController = new UniversityController();
        $this->ratingController = new RatingController();
        $this->registerController = new RegisterController();

        /** Entity Manager **/
        $this->entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        /** Repositories **/
        $this->courseRepository = $this->entityManager->getRepository(Course::class);
        $this->degreeRepository = $this->entityManager->getRepository(Degree::class);
        $this->userRepository = $this->entityManager->getRepository(User::class);
        $this->fileRepository = $this->entityManager->getRepository(File::class);
        $this->universityRepository = $this->entityManager->getRepository(University::class);
        $this->ratingRepository = $this->entityManager->getRepository(Rating::class);

        /** Serializer **/
        $this->serializer = static::$kernel->getContainer()->get('jms_serializer');

        /** Manager Registry **/
        $this->managerRegistry = static::$kernel->getContainer()->get('doctrine');

        parent::setUp();
    }

}