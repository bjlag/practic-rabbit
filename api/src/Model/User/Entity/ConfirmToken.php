<?php

namespace Api\Model\User\Entity;

use Webmozart\Assert\Assert;

class ConfirmToken
{
    private $token;
    private $expires;

    public function __construct(string $token, \DateTimeImmutable $expire)
    {
        Assert::notEmpty($token);

        $this->token = $token;
        $this->expires = $expire;
    }

    public function isEqualTo(string $token): bool
    {
        return $this->token === $token;
    }

    public function isExpiredTo(\DateTimeImmutable $date): bool
    {
        return $this->expires < $date;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getExpires(): \DateTimeImmutable
    {
        return $this->expires;
    }
}