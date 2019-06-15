<?php

namespace Api\Model\OAuth\Entity;

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\ScopeTrait;

class ScopeEntity implements ScopeEntityInterface
{
    use EntityTrait, ScopeTrait;

    public function __construct(string $identifier)
    {
        $this->identifier = $identifier;
    }
}
