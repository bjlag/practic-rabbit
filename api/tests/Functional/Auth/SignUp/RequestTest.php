<?php

namespace Api\Test\Functional\Auth\SignUp;

use Api\Test\Functional\WebTestCase;

class RequestTest extends WebTestCase
{
    protected function setUp(): void
    {
        $this->loadFixtures([
            RequestFixture::class
        ]);

        parent::setUp();
    }

    public function testMethod(): void
    {
        $response = $this->get('/auth/signup');
        self::assertEquals(405, $response->getStatusCode());
    }

    public function testUserExist(): void
    {
        $response = $this->post('/auth/signup', [
            'email' => $email = 'user@email.com',
            'password' => 'test-password',
        ]);

        self::assertEquals(400, $response->getStatusCode());
        self::assertJson($content = $response->getBody());

        $data = json_decode($content, true);

        self::assertEquals([
            'error' => 'User with this email already exists.',
        ], $data);
    }

    public function testSuccess(): void
    {
        $response = $this->post('/auth/signup', [
            'email' => $email = 'test-user@email.com',
            'password' => 'test-password',
        ]);

        self::assertEquals(201, $response->getStatusCode());
        self::assertJson($content = $response->getBody());

        $data = json_decode($content, true);

        self::assertEquals([
            'email' => $email,
        ], $data);
    }
}