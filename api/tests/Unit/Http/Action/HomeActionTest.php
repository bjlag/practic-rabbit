<?php

namespace Api\Test\Unit\Http\Action;

use Api\Http\Action\HomeAction;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ServerRequest;

class HomeActionTest extends TestCase
{
    public function testSuccess()
    {
        $action = new HomeAction();
        $response = $action->handle(new ServerRequest());

        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($content = $response->getBody());

        $data = json_decode($content, true);

        self::assertEquals([
            'name' => 'API app',
            'version' => '1.0',
        ], $data);
    }
}