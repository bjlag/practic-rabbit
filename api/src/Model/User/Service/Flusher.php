<?php

namespace Api\Model\User\Service;

interface Flusher
{
    public function flush($entity = null): void;
}