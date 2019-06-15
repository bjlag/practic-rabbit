<?php

declare(strict_types=1);

namespace Api\Http\Action\Auth\SignUp;

use Api\Http\ValidationException;
use Api\Http\Validator\SymfonyValidator;
use Api\Model\User\UseCase\SignUp\Request\Command;
use Api\Model\User\UseCase\SignUp\Request\Handler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class RequestAction implements RequestHandlerInterface
{
    private $handler;
    private $validator;

    public function __construct(Handler $handler, SymfonyValidator $validator)
    {
        $this->handler = $handler;
        $this->validator = $validator;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        $command = new Command();
        $command->email = $body['email'] ?? '';
        $command->password = $body['password'] ?? '';

        if ($errors = $this->validator->validate($command)) {
            throw new ValidationException($errors);
        }

        $this->handler->handle($command);

        return new JsonResponse([
            'email' => $body['email'],
        ], 201);
    }
}