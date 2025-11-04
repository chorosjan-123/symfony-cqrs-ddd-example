<?php

namespace App\ProcessFeature\Application\Handler;

use App\ProcessFeature\Application\Query\GetLastProcessEventsQuery;
use App\ProcessFeature\Domain\Repository\EventRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;


#[AsMessageHandler]
class GetLastProcessEventsHandler
{
    private EventRepositoryInterface $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function __invoke(GetLastProcessEventsQuery $query): ?array
    {
        return $this->eventRepository->findLastAsArray();
    }
}