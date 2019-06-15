<?php

namespace Api\Infrastructure\Model\OAuth\Entity;

use Api\Model\OAuth\Entity\RefreshTokenEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    private $em;
    /** @var EntityRepository */
    private $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(RefreshTokenEntity::class);
    }

    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        if ($this->exist($refreshTokenEntity->getIdentifier())) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }

        $this->em->persist($refreshTokenEntity);
        $this->em->flush();
    }

    public function revokeRefreshToken($tokenId)
    {
        if ($token = $this->repo->find($tokenId)) {
            $this->em->remove($token);
            $this->em->flush();
        }
    }

    public function isRefreshTokenRevoked($tokenId)
    {
        return !$this->exist($tokenId);
    }

    public function getNewRefreshToken()
    {
        return new RefreshTokenEntity();
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