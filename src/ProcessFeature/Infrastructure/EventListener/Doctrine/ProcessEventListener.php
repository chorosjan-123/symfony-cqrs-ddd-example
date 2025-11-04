<?php

namespace App\ProcessFeature\Infrastructure\EventListener\Doctrine;

use App\ProcessFeature\Domain\Entity\Event;
use App\ProcessFeature\Domain\Entity\Process;
use App\ProcessFeature\Domain\Repository\EventRepositoryInterface;
use App\ProcessFeature\Domain\ValueObject\ProcessEventAction;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class ProcessEventListener
{
    private EventRepositoryInterface $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Process) {
            return;
        }

        $event = new Event();
        $event->setProcessId($entity->getId());
        $event->setProcessTitle($entity->getTitle());
        $event->setAction(ProcessEventAction::created->value);
        $this->eventRepository->save($event);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Process) {
            return;
        }

        $event = new Event();
        $event->setProcessId($entity->getId());
        $event->setProcessTitle($entity->getTitle());
        $event->setAction(ProcessEventAction::updated->value);
        $this->eventRepository->save($event);
    }

    public function preRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Process) {
            return;
        }

        $event = new Event();
        $event->setProcessId($entity->getId());
        $event->setProcessTitle($entity->getTitle());
        $event->setAction(action: ProcessEventAction::deleted->value);
        $this->eventRepository->save($event);
    }
}