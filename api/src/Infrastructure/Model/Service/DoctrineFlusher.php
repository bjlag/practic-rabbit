<?php

namespace Api\Infrastructure\Model\Service;

use Api\Model\User\Service\Flusher;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineFlusher implements Flusher
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function flush(): void
    {
        $this->em->flush();
    }
}