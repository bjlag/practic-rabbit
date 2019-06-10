<?php

namespace Api\Infrastructure\Framework\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CallableRequestHandler implements RequestHandlerInterface
{
    private $callback;
    private $response;

    public function __construct(callable $callback, ResponseInterface $response)
    {
        $this->callback = $callback;
        $this->response = $response;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return ($this->callback)($request, $this->response);
    }
}