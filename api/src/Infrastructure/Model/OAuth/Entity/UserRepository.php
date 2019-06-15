<?php

namespace Api\Infrastructure\Model\OAuth\Entity;

use Api\Model\OAuth\Entity\UserEntity;
use Api\Model\User\Entity\Email;
use Api\Model\User\Entity\User;
use Api\Model\User\Service\PasswordHasher;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    private $em;
    /** @var EntityRepository */
    private $repo;
    private $hasher;

    public function __construct(EntityManagerInterface $em, PasswordHasher $hasher)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(User::class);
        $this->hasher = $hasher;
    }

    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ): ?UserEntityInterface
    {
        /** @var User $user */
        if ($user = $this->repo->findOneBy(['email' => new Email($username)])) {
            if ($this->hasher->validate($password, $user->getPasswordHash())) {
                return new UserEntity($user->getId()->getId());
            }
        }

        return null;
    }
}