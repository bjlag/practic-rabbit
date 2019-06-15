<?php

namespace Api\Test\Functional\Profile;

use Api\Model\OAuth\Entity\AccessTokenEntity;
use Api\Model\OAuth\Entity\ClientEntity;
use Api\Model\OAuth\Entity\ScopeEntity;
use Api\Model\User\Entity\Email;
use Api\Model\User\Entity\User;
use Api\Test\Builder\UserBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use League\OAuth2\Server\CryptKey;

class ShowFixture extends AbstractFixture
{
    private $token;
    private $user;

    public function load(ObjectManager $manager): void
    {
        $user = (new UserBuilder())
            ->withEmail(new Email('login@email.com'))
            ->build();

        $user->confirmSignup($user->getConfirmToken()->getToken(), new \DateTimeImmutable());

        $token = new AccessTokenEntity();
        $token->setIdentifier(bin2hex(random_bytes(40)));
        $token->setExpiryDateTime(new \DateTime('+1 hour'));
        $token->setUserIdentifier($user->getId()->getId());
        $token->setClient(new ClientEntity('app'));
        $token->addScope(new ScopeEntity('common'));

        $manager->persist($user);
        $manager->persist($token);
        $manager->flush();

        $this->user = $user;

        $this->token = (string)$token->convertToJWT(
            new CryptKey(dirname(__DIR__, 3) . '/' . getenv('OAUTH_PRIVATE_KEY_PATH'), null, false)
        );
    }

    public function getAuthorizationHeader(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->token
        ];
    }

    public function getUser(): User
    {
        return $this->user;
    }
}