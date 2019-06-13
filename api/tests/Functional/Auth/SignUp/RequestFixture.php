<?php

namespace Api\Test\Functional\Auth\SignUp;

use Api\Test\Builder\UserBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class RequestFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $user = (new UserBuilder())->build();
        $user->confirmSignup($user->getConfirmToken()->getToken(), new \DateTimeImmutable());

        $manager->persist($user);
        $manager->flush();
    }
}