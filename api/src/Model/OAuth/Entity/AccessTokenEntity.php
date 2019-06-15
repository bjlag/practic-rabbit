<?php

namespace Api\Model\OAuth\Entity;

use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

/**
 * @ORM\Entity()
 * @ORM\Table(name="oauth_access_tokens")
 */
class AccessTokenEntity implements AccessTokenEntityInterface
{
    use AccessTokenTrait, TokenEntityTrait, EntityTrait;

    /**
     * @ORM\Column(type="string", length=80)
     * @ORM\Id()
     */
    protected $identifier;

    /**
     * @ORM\Column(type="datetime", name="expiry_date_time")
     */
    protected $expiryDateTime;

    /**
     * @ORM\Column(type="guid", name="user_identifier")
     */
    protected $userIdentifier;

    /**
     * @ORM\Column(type="oauth_client")
     */
    protected $client;

    /**
     * @ORM\Column(type="oauth_scopes")
     */
    protected $scopes = [];
}