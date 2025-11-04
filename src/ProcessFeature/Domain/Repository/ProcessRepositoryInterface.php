<?php

namespace App\ProcessFeature\Domain\Repository;

use App\ProcessFeature\Domain\Entity\Process;
use Doctrine\DBAL\LockMode;

/**
 * Interface ProcessRepositoryInterace
 */
interface ProcessRepositoryInterface
{
    public function find(mixed $id, LockMode|int|null $lockMode = null, ?int $lockVersion = null): ?object;
    public function save(Process $process): void;
    public function delete(Process $process): void;
    public function findAllAsArray(?string $statusFilter = null): ?array;
    public function findByIdAsArray(int $id): ?array;
    public function findByTitle(string $title): ?Process;
}
