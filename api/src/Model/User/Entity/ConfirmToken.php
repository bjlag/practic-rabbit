<?php

namespace Api\Model\User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;


/**
 * @ORM\Embeddable
 */
class ConfirmToken
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
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

    public function isEmpty(): bool
    {
        return empty($this->token);
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