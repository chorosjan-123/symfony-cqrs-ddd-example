<?php

namespace App\ProcessFeature\Presentation\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use App\ProcessFeature\Application\Query\GetLastProcessEventsQuery;

#[Route('/events', name: 'events_')]
class EventController extends AbstractController
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus) {
        $this->messageBus = $messageBus;
    }

    #[Route('', name: 'event_get_last', methods: ['GET'])]
    public function getEvents(): JsonResponse
    {
        $envelope = $this->messageBus->dispatch(new GetLastProcessEventsQuery());
        $stamp = $envelope->last(HandledStamp::class);
        $events = $stamp?->getResult();

        return new JsonResponse([
            'success' => 'success',
            'events' => $events,
        ], JsonResponse::HTTP_OK);
    }
}