<?php

namespace Api\Test\Functional\Auth\SignUp;

use Api\Model\User\Entity\ConfirmToken;
use Api\Model\User\Entity\Email;
use Api\Test\Builder\UserBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class ConfirmFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $confirm = (new UserBuilder())
            ->withEmail(new Email('confirm@email.com'))
            ->build();

        $expired = (new UserBuilder())
            ->withEmail(new Email('expired@email.com'))
            ->withConfirmToken(new ConfirmToken('token', new \DateTimeImmutable('-1 day')))
            ->build();

        $active = (new UserBuilder())
            ->withEmail(new Email('active@email.com'))
            ->build();

        $active->confirmSignup($active->getConfirmToken()->getToken(), new \DateTimeImmutable());

        $manager->persist($confirm);
        $manager->persist($expired);
        $manager->persist($active);
        $manager->flush();
    }
}