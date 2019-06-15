<?php

namespace Api\Model\OAuth\Entity;

use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\Traits\AuthCodeTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

/**
 * @ORM\Entity()
 * @ORM\Table(name="oauth_auth_codes")
 */
class AuthCodeEntity implements AuthCodeEntityInterface
{
    use EntityTrait, TokenEntityTrait, AuthCodeTrait;

    /**
     * @ORM\Column(type="string")
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

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $redirectUri;
}