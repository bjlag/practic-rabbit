<?php

namespace Api\Http\Action\Auth;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;

class OAuthAction implements RequestHandlerInterface
{
    private $server;

    public function __construct(AuthorizationServer $server)
    {
        $this->server = $server;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            return $this->server->respondToAccessTokenRequest($request, new Response());

        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse(new Response());

        } catch (\Exception $exception) {
            return (new OAuthServerException($exception->getMessage(), 0, 'unknown_error', 500))
                ->generateHttpResponse(new Response());
        }
    }
}