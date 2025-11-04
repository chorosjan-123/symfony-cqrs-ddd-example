<?php

namespace App\ProcessFeature\Infrastructure\Repository;

use App\ProcessFeature\Domain\Entity\Event;
use App\ProcessFeature\Domain\Repository\EventRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository implements EventRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function save(Event $event): void
    {
        $this->getEntityManager()->persist($event);
        $this->getEntityManager()->flush();
    }

    /**
     * Gets last 100 records
     * @return array|null
     */
    public function findLastAsArray(): ?array
    {
    $result = $this->createQueryBuilder('e')
        ->select('e.id', 'e.action', 'e.processId', 'e.processTitle', 'e.createdAt')
        ->orderBy('e.createdAt', 'DESC')
        ->setMaxResults(100)
        ->getQuery()
        ->getArrayResult();

        if (empty($result)) {
            $result = null;
        }

        return $result;
    }
}
