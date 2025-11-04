<?php

namespace App\ProcessFeature\Domain\Repository;

use App\ProcessFeature\Domain\Entity\Event;

/**
 * Interface ProcessRepositoryInterace
 */
interface EventRepositoryInterface
{
    public function save(Event $event): void;
    public function findLastAsArray(): ?array;
}
