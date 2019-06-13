<?php

namespace Api\Test\Functional\Auth\SignUp;

use Api\Test\Functional\WebTestCase;

class ConfirmTest extends WebTestCase
{
    protected function setUp(): void
    {
        $this->loadFixtures([
            ConfirmFixture::class
        ]);

        parent::setUp();
    }

    public function testMethod(): void
    {
        $response = $this->get('/auth/signup/confirm');
        self::assertEquals(405, $response->getStatusCode());
    }

    public function testUserActive(): void
    {
        $response = $this->post('/auth/signup/confirm', [
            'email' => $email = 'active@email.com',
            'token' => 'token',
        ]);

        self::assertEquals(400, $response->getStatusCode());
        self::assertJson($content = $response->getBody());

        $data = json_decode($content, true);

        self::assertEquals([
            'error' => 'User is already active.',
        ], $data);
    }

    public function testUserNotFound(): void
    {
        $response = $this->post('/auth/signup/confirm', [
            'email' => $email = 'invalid@email.com',
            'token' => 'token',
        ]);

        self::assertEquals(400, $response->getStatusCode());
        self::assertJson($content = $response->getBody());

        $data = json_decode($content, true);

        self::assertEquals([
            'error' => 'User is not found.',
        ], $data);
    }

    public function testTokenExpired(): void
    {
        $response = $this->post('/auth/signup/confirm', [
            'email' => $email = 'expired@email.com',
            'token' => 'token',
        ]);

        self::assertEquals(400, $response->getStatusCode());
        self::assertJson($content = $response->getBody());

        $data = json_decode($content, true);

        self::assertEquals([
            'error' => 'Confirm token is expired.',
        ], $data);
    }

    public function testTokenInvalid(): void
    {
        $response = $this->post('/auth/signup/confirm', [
            'email' => $email = 'confirm@email.com',
            'token' => 'token_invalid',
        ]);

        self::assertEquals(400, $response->getStatusCode());
        self::assertJson($content = $response->getBody());

        $data = json_decode($content, true);

        self::assertEquals([
            'error' => 'Confirm token is invalid.',
        ], $data);
    }

    public function testSuccess(): void
    {
        $response = $this->post('/auth/signup/confirm', [
            'email' => $email = 'confirm@email.com',
            'token' => 'token',
        ]);

        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($content = $response->getBody());

        $data = json_decode($content, true);

        self::assertEquals([], $data);
    }
}