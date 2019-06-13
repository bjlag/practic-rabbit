<?php

namespace Api\Model;

interface EventDispatcher
{
    public function dispatch(array $events): void;
}