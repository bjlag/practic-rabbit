<?php

namespace Api\Test\Functional\Auth\SignUp;

use Api\Test\Functional\Profile\ShowFixture;
use Api\Test\Functional\WebTestCase;

class ShowTest extends WebTestCase
{
    protected function setUp(): void
    {
        $this->loadFixtures([
            'auth' => ShowFixture::class
        ]);

        parent::setUp();
    }

    public function testMethod(): void
    {
        $response = $this->post('/profile');
        self::assertEquals(405, $response->getStatusCode());
    }

    public function testAccess(): void
    {
        $response = $this->get('/profile');
        self::assertEquals(401, $response->getStatusCode());
    }

    public function testSuccess(): void
    {
        /** @var ShowFixture $fixture */
        $fixture = $this->getFixture('auth');

        $response = $this->get('/profile', $fixture->getAuthorizationHeader());

        self::assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody()->getContents(), true);

        self::assertArrayHasKey('id', $data);
        self::assertEquals($data['id'], $fixture->getUser()->getId()->getId());
    }
}