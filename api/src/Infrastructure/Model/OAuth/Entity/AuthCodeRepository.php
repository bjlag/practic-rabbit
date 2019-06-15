<?php

namespace Api\Infrastructure\Model\OAuth\Entity;

use Api\Model\OAuth\Entity\AuthCodeEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

class AuthCodeRepository implements AuthCodeRepositoryInterface
{
    private $em;
    /** @var EntityRepository */
    private $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(AuthCodeEntity::class);
    }

    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity): void
    {
        if ($this->exist($authCodeEntity->getIdentifier())) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }

        $this->em->persist($authCodeEntity);
        $this->em->flush();
    }

    public function revokeAuthCode($codeId): void
    {
        if ($code = $this->repo->find($codeId)) {
            $this->em->remove($code);
            $this->em->flush();
        }
    }

    public function isAuthCodeRevoked($codeId): bool
    {
        return !$this->exist($codeId);
    }

    public function getNewAuthCode(): AuthCodeEntityInterface
    {
        return new AuthCodeEntity();
    }

    private function exist(string $id): bool
    {
        return $this->repo->createQueryBuilder('t')
            ->select('COUNT(t.identifier)')
            ->andWhere('t.identifier = :identifier')
            ->setParameter('identifier', $id)
            ->getQuery()->getSingleScalarResult() > 0;
    }
}
