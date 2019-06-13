<?php

namespace Api\Test\Builder;

use Api\Model\User\Entity\ConfirmToken;
use Api\Model\User\Entity\Email;
use Api\Model\User\Entity\User;
use Api\Model\User\Entity\UserId;

class UserBuilder
{
    private $id;
    private $date;
    private $email;
    private $passwordHash;
    private $confirmToken;

    public function __construct()
    {
        $this->id = UserId::next();
        $this->date = new \DateTimeImmutable();
        $this->email = new Email('user@email.com');
        $this->passwordHash = '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm'; // 'secret'
        $this->confirmToken = new ConfirmToken('token', new \DateTimeImmutable('+1 day'));
    }

    public function withId(UserId $id): self
    {
        $clone = clone $this;
        $clone->id = $id;

        return $clone;
    }

    public function withDate(\DateTimeImmutable $date): self
    {
        $clone = clone $this;
        $clone->date = $date;

        return $clone;
    }

    public function withEmail(Email $email): self
    {
        $clone = clone $this;
        $clone->email = $email;

        return $clone;
    }

    public function withPasswordHash(string $passwordHash): self
    {
        $clone = clone $this;
        $clone->passwordHash = $passwordHash;

        return $clone;
    }

    public function withConfirmToken(ConfirmToken $confirmToken): self
    {
        $clone = clone $this;
        $clone->confirmToken = $confirmToken;

        return $clone;
    }

    public function build(): User
    {
        return new User(
            $this->id,
            $this->date,
            $this->email,
            $this->passwordHash,
            $this->confirmToken
        );
    }
}