<?php

namespace Api\Model\User\Entity;

interface UserRepository
{
    public function findByEmail(Email $email): User;
    public function hasByEmail(Email $email): bool;
    public function add(User $user): void;
}