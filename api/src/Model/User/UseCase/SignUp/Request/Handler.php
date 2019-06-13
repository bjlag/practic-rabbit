<?php

namespace Api\Model\User\UseCase\SignUp\Request;

use Api\Model\User\Entity\Email;
use Api\Model\User\Entity\User;
use Api\Model\User\Entity\UserId;
use Api\Model\User\Entity\UserRepository;
use Api\Model\User\Service\ConfirmTokenizer;
use Api\Model\User\Service\Flusher;
use Api\Model\User\Service\PasswordHasher;

class Handler
{
    private $users;
    private $hasher;
    private $tokenizer;
    private $flusher;

    public function __construct(
        UserRepository $users,
        PasswordHasher $hasher,
        ConfirmTokenizer $tokenizer,
        Flusher $flusher
    )
    {
        $this->users = $users;
        $this->hasher = $hasher;
        $this->tokenizer = $tokenizer;
        $this->flusher = $flusher;
    }

    public function handle(Command $command)
    {
        $email = new Email($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new \DomainException('User with this email already exists.');
        }

        $user = new User(
            UserId::next(),
            new \DateTimeImmutable(),
            $email,
            $this->hasher->hash($command->password),
            $this->tokenizer->generate()
        );

        $this->users->add($user);
        $this->flusher->flush($user);
    }
}