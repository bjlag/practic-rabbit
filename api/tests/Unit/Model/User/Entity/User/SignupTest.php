<?php

namespace Api\Test\Unit\Model\User\Entity\User;

use Api\Model\User\Entity\ConfirmToken;
use Api\Model\User\Entity\Email;
use Api\Model\User\Entity\UserId;
use Api\Test\Builder\UserBuilder;
use PHPUnit\Framework\TestCase;

class SignupTest extends TestCase
{
    public function testSuccess()
    {
        $user = (new UserBuilder())
            ->withId($id = UserId::next())
            ->withDate($date = new \DateTimeImmutable())
            ->withEmail($email = new Email('user@email.com'))
            ->withPasswordHash($hash = 'hash')
            ->withConfirmToken($token = new ConfirmToken('token', new \DateTimeImmutable()))
            ->build();

        self::assertEquals($user->getId(), $id);
        self::assertEquals($user->getDate(), $date);
        self::assertEquals($user->getEmail(), $email);
        self::assertEquals($user->getPasswordHash(), $hash);
        self::assertEquals($user->getConfirmToken(), $token);
        self::assertTrue($user->isWait());
    }
}