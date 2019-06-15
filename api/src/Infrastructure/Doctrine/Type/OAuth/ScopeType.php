<?php

namespace Api\Infrastructure\Doctrine\Type\OAuth;

use Api\Model\OAuth\Entity\ScopeEntity;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

class ScopeType extends JsonType
{
    const NAME = 'oauth_scopes';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $data = array_map(function (ScopeEntity $entity) {
            return $entity->getIdentifier();
        }, $value);

        return parent::convertToDatabaseValue($data, $platform);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $values = parent::convertToDatabaseValue($value, $platform);

        if ($value) {
            return array_map(function (string $identifier) {
                return new ScopeEntity($identifier);
            }, $values);
        }

        return [];
    }
}