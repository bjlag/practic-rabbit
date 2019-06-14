<?php

namespace Api\Test\Functional\Auth\OAuth;

use Api\Test\Functional\WebTestCase;

class LoginTest extends WebTestCase
{
    protected function setUp(): void
    {
        $this->loadFixtures([
            LoginFixture::class
        ]);

        parent::setUp();
    }

    public function testMethod(): void
    {
        $response = $this->get('/auth/oauth/login');
        self::assertEquals(405, $response->getStatusCode());
    }

    public function testSuccess(): void
    {
        $response = $this->post('/auth/oauth/login', [
            'grand_type' => 'password',
            'username' => 'login@email.com',
            'password' => 'secret',
            'client_id' => 'app',
            'client_secret' => '',
            'access_type' => 'offline',
        ]);

        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($content = $response->getBody());

        $data = json_decode($content, true);

        self::assertArrayHasKey('token_type', $data);
        self::assertEquals('Bearer', $data['token_type']);

        self::assertArrayHasKey('expires_in', $data);
        self::assertNotEmpty($data['expires_in']);

        self::assertArrayHasKey('access_token', $data);
        self::assertNotEmpty($data['access_token']);

        self::assertArrayHasKey('refresh_token', $data);
        self::assertNotEmpty($data['refresh_token']);
    }

    public function testFail(): void
    {
        $response = $this->post('/auth/oauth/login', [
            'grand_type' => 'password',
            'username' => 'login@email.com',
            'password' => 'wrong_pass',
            'client_id' => 'app',
            'client_secret' => '',
            'access_type' => 'offline',
        ]);

        self::assertEquals(401, $response->getStatusCode());
    }
}