<?php

namespace Api\Http\Action;

use Slim\Http\Request;
use Slim\Http\Response;

class HomeAction
{
    public function __invoke(Request $request, Response $response)
    {
        return $response->withJson([
            'name' => 'API app',
            'version' => '1.0',
        ]);
    }
}