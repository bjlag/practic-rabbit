<?php

namespace Api\Infrastructure\Model\User\Service;

use Api\Model\User\Service\PasswordHasher;

class BCryptPasswordHasher implements PasswordHasher
{
    private $cost;

    public function __construct(int $cost = 12)
    {
        $this->cost = $cost;
    }

    public function hash(string $password): string
    {
        if (($hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => $this->cost])) !== false) {
            return $hash;
        }

        throw new \RuntimeException('Unable to generate password hash.');
    }

    public function validate(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}