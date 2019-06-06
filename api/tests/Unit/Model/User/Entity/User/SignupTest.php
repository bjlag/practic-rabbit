<?php

namespace Api\Test\Unit\Model\User\Entity\User;

use Api\Model\User\Entity\ConfirmToken;
use Api\Model\User\Entity\Email;
use Api\Model\User\Entity\User;
use Api\Model\User\Entity\UserId;
use PHPUnit\Framework\TestCase;

class SignupTest extends TestCase
{
    public function testSuccess()
    {
        $user = new User(
            $id = UserId::next(),
            $date = new \DateTimeImmutable(),
            $email = new Email('user@email.com'),
            $hash = 'hash',
            $token = new ConfirmToken('token', new \DateTimeImmutable())
        );

        self::assertEquals($user->getId(), $id);
        self::assertEquals($user->getDate(), $date);
        self::assertEquals($user->getEmail(), $email);
        self::assertEquals($user->getPasswordHash(), $hash);
        self::assertEquals($user->getConfirmToken(), $token);
        self::assertTrue($user->isWait());
    }
}