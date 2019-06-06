<?php

namespace Api\Infrastructure\Doctrine\Type\User;

use Api\Model\User\Entity\UserId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class UserIdType extends GuidType
{
    const NAME = 'user_user_id';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof UserId ? $value->getId() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new UserId($value) : null;
    }
}