<?php

namespace App\Tests\Controller;

use App\Factory\UserFactory;
use App\Tests\BaseTest;
use Symfony\Component\HttpFoundation\Request;
use function Zenstruck\Foundry\faker;

class RegisterControllerTest extends BaseTest
{

    public function testRegister()
    {
        $request = Request::create(
            '/api/register',
            'POST',
            [],
            [],
            [],
            [],
            json_encode(['email' => faker()->email(), 'sub' => faker()->uuid()])
        );

        $response = $this->registerController->index($this->managerRegistry, $request);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRegisterAlreadyRegistered()
    {
        $user = UserFactory::createOne();
        $request = Request::create(
            '/api/register',
            'POST',
            [],
            [],
            [],
            [],
            json_encode(['email' => $user->getEmail(), 'sub' => faker()->uuid()])
        );

        $response = $this->registerController->index($this->managerRegistry, $request);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRegisterInvalidRequest()
    {
        $user = UserFactory::createOne();
        $request = Request::create(
            '/api/register',
            'POST',
            [],
            [],
            [],
            [],
            json_encode(['mail' => $user->getEmail(), 'sub' => faker()->uuid()])
        );

        $response = $this->registerController->index($this->managerRegistry, $request);
        $this->assertEquals(200, $response->getStatusCode());
    }


}