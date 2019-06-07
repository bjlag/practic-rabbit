<?php

namespace Api\Test\Functional\Auth\SignUp;

use Api\Model\User\Entity\ConfirmToken;
use Api\Model\User\Entity\Email;
use Api\Model\User\Entity\User;
use Api\Model\User\Entity\UserId;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class RequestFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User(
            UserId::next(),
            new \DateTimeImmutable(),
            new Email('user@email.com'),
            '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // 'secret'
            $token = new ConfirmToken('token', new \DateTimeImmutable('+1 day'))
        );

        $user->confirmSignup($token->getToken(), new \DateTimeImmutable());

        $manager->persist($user);
        $manager->flush();
    }
}