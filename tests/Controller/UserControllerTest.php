<?php

namespace App\Tests\Controller;

use App\Factory\UserFactory;
use App\Tests\BaseTest;

class UserControllerTest extends BaseTest
{
    public function testIndexUser()
    {
        $user = UserFactory::createOne();

        $response = $this->userController->indexUser($this->userRepository, $this->serializer, $user->getSub());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals($user->getEmail(), json_decode($response->getContent())->email);
    }

    public function testIndexUserNotFound()
    {
        $response = $this->userController->indexUser($this->userRepository, $this->serializer, 'notfound');
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testDeleteUser()
    {
        $user = UserFactory::createOne();

        $response = $this->userController->deleteUser($this->managerRegistry, $this->userRepository, $user->getSub());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteUserNotFound()
    {
        $response = $this->userController->deleteUser($this->managerRegistry, $this->userRepository, 'notfound');
        $this->assertEquals(404, $response->getStatusCode());
    }


    public function testOptionsUsersRegister()
    {
        $response = $this->userController->optionsUsersRegister();
        $this->assertEquals(204, $response->getStatusCode());

        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('POST, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type, Authorization', $response->headers->get('Access-Control-Allow-Headers'));
    }

    public function testOptionsUsersSub()
    {
        $response = $this->userController->optionsUsersSub();
        $this->assertEquals(204, $response->getStatusCode());

        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertEquals('GET, DELETE, OPTIONS', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertEquals('Content-Type, Authorization', $response->headers->get('Access-Control-Allow-Headers'));
    }

}