<?php

namespace Api\Test\Unit\Model\User\Entity\User;

use Api\Model\User\Entity\ConfirmToken;
use Api\Model\User\Entity\User;
use Api\Test\Builder\UserBuilder;
use PHPUnit\Framework\TestCase;

class ConfirmTest extends TestCase
{
    public function testSuccess()
    {
        $now = new \DateTimeImmutable();
        $token = new ConfirmToken('token', $now->modify('+1 day'));

        $user = $this->signUp($token);

        self::assertEquals($user->getConfirmToken(), $token);
        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());

        $user->confirmSignup($token->getToken(), $now);

        self::assertNull($user->getConfirmToken());
        self::assertTrue($user->isActive());
        self::assertFalse($user->isWait());
    }

    public function testAlreadyActive()
    {
        $now = new \DateTimeImmutable();
        $token = new ConfirmToken('token', $now->modify('+1 day'));

        $user = $this->signUp($token);
        $user->confirmSignup($token->getToken(), $now);

        $this->expectExceptionMessage('User is already active.');
        $user->confirmSignup($token->getToken(), $now);
    }

    public function testInvalidToken()
    {
        $now = new \DateTimeImmutable();
        $token = new ConfirmToken('token', $now->modify('+1 day'));

        $user = $this->signUp($token);

        $this->expectExceptionMessage('Confirm token is invalid.');
        $user->confirmSignup('invalid', $now);
    }

    public function testTokenExpired()
    {
        $now = new \DateTimeImmutable();
        $token = new ConfirmToken('token', $now->modify('-1 day'));

        $user = $this->signUp($token);

        $this->expectExceptionMessage('Confirm token is expired.');
        $user->confirmSignup($token->getToken(), $now);
    }

    private function signUp(ConfirmToken $token): User
    {
        return (new UserBuilder())
            ->withConfirmToken($token)
            ->build();
    }
}