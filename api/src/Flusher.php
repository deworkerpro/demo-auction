<?php

declare(strict_types=1);

namespace App;

use Doctrine\ORM\EntityManagerInterface;

final class Flusher
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function flush(): void
    {
        $this->em->flush();
    }
}
