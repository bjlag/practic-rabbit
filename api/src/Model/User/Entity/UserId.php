<?php

namespace Api\Model\User\Entity;

use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

class UserId
{
    private $id;

    public function __construct(string $id)
    {
        Assert::notEmpty($id);
        $this->id = $id;
    }

    public static function next(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}