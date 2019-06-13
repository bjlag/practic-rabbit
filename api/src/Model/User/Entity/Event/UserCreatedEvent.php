<?php

namespace Api\Model\User\Entity\Event;

use Api\Model\User\Entity\ConfirmToken;
use Api\Model\User\Entity\Email;
use Api\Model\User\Entity\UserId;

class UserCreatedEvent
{
    private $id;
    private $email;
    private $token;

    public function __construct(UserId $id, Email $email, ConfirmToken $token)
    {
        $this->id = $id;
        $this->email = $email;
        $this->token = $token;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getToken(): ConfirmToken
    {
        return $this->token;
    }
}