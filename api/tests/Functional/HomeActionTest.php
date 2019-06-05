<?php

namespace Api\Test\Functional\Http\Action;

use Api\Test\Functional\WebTestCase;

class HomeActionTest extends WebTestCase
{
    public function testSuccess()
    {
        $response = $this->get('/');

        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($content = $response->getBody());

        $data = json_decode($content, true);

        self::assertEquals([
            'name' => 'API app',
            'version' => '1.0',
        ], $data);
    }
}