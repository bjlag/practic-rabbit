<?php

namespace Api\Model\User\UseCase\SignUp\Confirm;

use Api\Model\User\Entity\Email;
use Api\Model\User\Entity\UserRepository;
use Api\Model\User\Service\Flusher;

class Handler
{
    private $users;
    private $flusher;

    public function __construct(UserRepository $users, Flusher $flusher)
    {
        $this->users = $users;
        $this->flusher = $flusher;
    }

    public function handle(Command $command)
    {
        $user = $this->users->findByEmail(new Email($command->email));

        $user->confirmSignup($command->token, new \DateTimeImmutable());

        $this->flusher->flush();
    }
}