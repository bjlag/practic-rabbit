<?php

namespace Api\Model\User\Entity;

class User
{
    const STATUS_WAIT = 1;
    const STATUS_ACTIVE = 2;

    private $id;
    private $date;
    private $email;
    private $passwordHash;
    private $confirmToken;
    private $status;

    public function __construct(
        UserId $id,
        \DateTimeImmutable $date,
        Email $email,
        string $passwordHash,
        ConfirmToken $confirmToken
    )
    {
        $this->id = $id;
        $this->date = $date;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->confirmToken = $confirmToken;
        $this->status = self::STATUS_WAIT;
    }

    public function confirmSignup(string $token, \DateTimeImmutable $date): void
    {
        if ($this->isActive()) {
            throw new \DomainException('User is already active.');
        }

        if (!$this->confirmToken->isEqualTo($token)) {
            throw new \DomainException('Confirm token is invalid.');
        }

        if ($this->confirmToken->isExpiredTo($date)) {
            throw new \DomainException('Confirm token is expired.');
        }

        $this->confirmToken = null;
        $this->status = self::STATUS_ACTIVE;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getConfirmToken(): ?ConfirmToken
    {
        return $this->confirmToken;
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}