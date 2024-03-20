<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testCreateUser(): void
    {
        $userData = [
            'email' => 'johndoe@company.com',
            'firstName' => 'John',
            'lastName' => 'Doe',
        ];

        $client = static::createClient();
        $client->request(
            'POST',
            '/users',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($userData)
        );

        $this->assertResponseIsSuccessful();
    }

    public function testCreateUserFailsWithoutCompleteUserData(): void
    {
        $userData = [
            'email' => '',
            'firstName' => 'John',
            'lastName' => 'Doe',
        ];

        $client = static::createClient();
        $client->request(
            'POST',
            '/users',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($userData)
        );

        $this->assertSame(500, $client->getResponse()->getStatusCode());
    }
}
