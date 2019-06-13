<?php

namespace Api\Model;

interface AggregateRoot
{
    public function releaseEvents(): array;
}