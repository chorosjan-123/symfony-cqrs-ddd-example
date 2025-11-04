<?php

namespace App\ProcessFeature\Infrastructure\EventListener\Doctrine;

use App\ProcessFeature\Domain\Entity\Process;

class ProcessProtectionListener
{
    public function preRemove($object): void
    {
         if (!$object instanceof Process) {
            return;
        }

        if ($object->isProtected()) {
            throw new \DomainException('Process is protected from deletion.');
        }
    }
}
