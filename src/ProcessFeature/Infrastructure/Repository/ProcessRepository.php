<?php

namespace App\ProcessFeature\Infrastructure\Repository;

use App\ProcessFeature\Domain\Entity\Process;
use App\ProcessFeature\Domain\Repository\ProcessRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Process>
 */
class ProcessRepository extends ServiceEntityRepository implements ProcessRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Process::class);
    }

     public function save(Process $process): void
    {
        $this->getEntityManager()->persist($process);
        $this->getEntityManager()->flush();
    }

    public function delete(Process $process): void
    {
        $this->getEntityManager()->remove($process);
        $this->getEntityManager()->flush();
    }

    public function findByTitle(string $title): ?Process
    {
        return $this->findOneBy(['title' => $title]);
    }

    public function findAllAsArray(?string $statusFilter = null): ?array
    {
        if ($statusFilter === null) {
            $result = $this->createQueryBuilder('t')
                ->select('t.id', 't.title', 't.description', 't.status', 't.createdAt', 't.updatedAt')
                ->getQuery()
                ->getArrayResult();
        } else {
            $result = $this->createQueryBuilder('t')
                ->select('t.id', 't.title', 't.description', 't.status', 't.createdAt', 't.updatedAt')
                ->where('t.status = :status')
                ->setParameter('status', $statusFilter)
                ->getQuery()
                ->getArrayResult();
        }
        if (empty($result)) {
            $result = null;
        }

        return $result;
    }

    public function findByIdAsArray(int $id): ?array
    {
        $result = $this->createQueryBuilder('t')
            ->select('t.id', 't.title', 't.description', 't.status', 't.createdAt', 't.updatedAt')
            ->where('t.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getArrayResult();

        if (empty($result)) {
            $result = null;
        }

        return $result;
    }
}
