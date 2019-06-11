<?php

namespace Api\Test\Functional\Auth\SignUp;

use Api\Model\User\Entity\ConfirmToken;
use Api\Model\User\Entity\Email;
use Api\Model\User\Entity\User;
use Api\Model\User\Entity\UserId;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class ConfirmFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $confirm = new User(
            UserId::next(),
            new \DateTimeImmutable(),
            new Email('confirm@email.com'),
            '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // 'secret'
            $token = new ConfirmToken('token_confirm', new \DateTimeImmutable('+1 day'))
        );

        $expired = new User(
            UserId::next(),
            new \DateTimeImmutable(),
            new Email('expired@email.com'),
            '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // 'secret'
            $token = new ConfirmToken('token_expired', new \DateTimeImmutable('-1 day'))
        );

        $active = new User(
            UserId::next(),
            new \DateTimeImmutable(),
            new Email('active@email.com'),
            '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // 'secret'
            $token = new ConfirmToken('token_active', new \DateTimeImmutable('+1 day'))
        );

        $active->confirmSignup($token->getToken(), new \DateTimeImmutable());

        $manager->persist($confirm);
        $manager->persist($expired);
        $manager->persist($active);
        $manager->flush();
    }
}