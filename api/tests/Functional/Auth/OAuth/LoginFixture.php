<?php

namespace Api\Test\Functional\Auth\OAuth;

use Api\Model\User\Entity\Email;
use Api\Test\Builder\UserBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoginFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $user = (new UserBuilder())
            ->withEmail(new Email('login@email.com'))
            ->build();

        $user->confirmSignup($user->getConfirmToken()->getToken(), new \DateTimeImmutable());

        $manager->persist($user);
        $manager->flush();
    }
}