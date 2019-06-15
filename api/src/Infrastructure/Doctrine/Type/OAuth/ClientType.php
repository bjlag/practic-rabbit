<?php

namespace Api\Infrastructure\Doctrine\Type\OAuth;

use Api\Model\OAuth\Entity\ClientEntity;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class ClientType extends StringType
{
    const NAME = 'oauth_client';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        /** @var $value ClientEntity */
        return $value instanceof ClientEntity ? $value->getIdentifier() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new ClientEntity($value) : null;
    }
}