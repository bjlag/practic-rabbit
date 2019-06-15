<?php

namespace Api\Infrastructure\Model\OAuth\Entity;

use Api\Model\OAuth\Entity\ScopeEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

class ScopeRepository implements ScopeRepositoryInterface
{
    private $scopes;

    public function __construct()
    {
        $this->scopes = [
            'common' => new ScopeEntity('common')
        ];
    }

    public function getScopeEntityByIdentifier($scopeIdentifier): ?ScopeEntityInterface
    {
        return $this->scopes[$scopeIdentifier] ?? null;
    }

    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ): array
    {
        return array_filter($scopes, function (ScopeEntityInterface $scope) {
            /** @var ScopeEntityInterface $item */
            foreach ($this->scopes as $item) {
                return $item->getIdentifier() === $scope->getIdentifier() ? true : false;
            }
        });
    }
}