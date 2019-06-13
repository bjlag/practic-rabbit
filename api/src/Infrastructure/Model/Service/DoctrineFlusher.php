<?php

namespace Api\Infrastructure\Model\Service;

use Api\Model\AggregateRoot;
use Api\Model\EventDispatcher;
use Api\Model\User\Service\Flusher;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineFlusher implements Flusher
{
    private $em;
    private $dispatcher;

    public function __construct(EntityManagerInterface $em, EventDispatcher $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    public function flush($entity = null): void
    {
        $this->em->flush();

        if ($entity instanceof AggregateRoot) {
            $this->dispatcher->dispatch($entity->releaseEvents());
        }
    }
}