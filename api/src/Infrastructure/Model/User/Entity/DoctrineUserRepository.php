<?php

namespace Api\Infrastructure\Model\User\Entity;

use Api\Model\User\Entity\Email;
use Api\Model\User\Entity\User;
use Api\Model\User\Entity\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;

class DoctrineUserRepository implements UserRepository
{
    /** @var EntityRepository */
    private $repo;
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(User::class);
    }

    public function findByEmail(Email $email): User
    {
        /** @var $user User */
        if (($user = $this->repo->findOneBy(['email' => $email->getEmail()])) !== null) {
            return $user;
        }

        throw new EntityNotFoundException('User is not found.');
    }

    public function hasByEmail(Email $email): bool
    {
        return $this->repo->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->andWhere('u.email = :email')
            ->setParameter(':email', $email->getEmail())
            ->getQuery()->getSingleScalarResult() > 0;
    }

    public function add(User $user): void
    {
        $this->em->persist($user);
    }
}