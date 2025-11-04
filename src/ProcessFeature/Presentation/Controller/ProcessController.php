<?php

namespace App\ProcessFeature\Presentation\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use App\ProcessFeature\Application\Command\ChangeProcessStatusCommand;
use App\ProcessFeature\Application\Command\CreateProcessCommand;
use App\ProcessFeature\Application\Command\DeleteProcessCommand;
use App\ProcessFeature\Application\Command\UpdateProcessCommand;
use App\ProcessFeature\Application\Query\GetAllProcessesQuery;
use App\ProcessFeature\Application\Query\GetProcessByIdQuery;
use App\ProcessFeature\Presentation\Request\DTO\ChangeProcessStatusRequestDto;
use App\ProcessFeature\Presentation\Request\DTO\CreateProcessRequestDto;
use App\ProcessFeature\Presentation\Request\DTO\UpdateProcessRequestDto;

#[Route('/process', name: 'process_')]
class ProcessController extends AbstractController
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus) {
        $this->messageBus = $messageBus;
    }

    #[Route('', name: 'process_create', methods: ['POST'])]
    public function create(#[MapRequestPayload] CreateProcessRequestDto $dto): JsonResponse
    {
        $this->messageBus->dispatch(
            new CreateProcessCommand(
                title: $dto->title,
                description: $dto->description,
                status: $dto->status
            )
        );

        // Vague response due to possible implementation wiht async.
        return new JsonResponse(['success' => 'success'], JsonResponse::HTTP_ACCEPTED);
    }

    #[Route('/{id}', name: 'process_update', methods: ['PATCH'])]
    public function update(int $id, #[MapRequestPayload] UpdateProcessRequestDto $dto): JsonResponse
    {
        $this->messageBus->dispatch(
            new UpdateProcessCommand(
                id: $id,
                title: $dto->title,
                description: $dto->description,
                status: $dto->status
            )
        );
    
        // Vague response due to possible implementation wiht async.
        return new JsonResponse(['success' => 'success'], JsonResponse::HTTP_ACCEPTED);
    }

    #[Route('/{id}/status', name: 'process_change_status', methods: ['PATCH'])]
    public function changeStatus(int $id, #[MapRequestPayload] ChangeProcessStatusRequestDto $dto): JsonResponse
    {
        $this->messageBus->dispatch(
            new ChangeProcessStatusCommand(
                id: $id,
                status: $dto->status
            )
        );

        // Vague response due to possible implementation wiht async.
        return new JsonResponse(['success' => 'success'], JsonResponse::HTTP_ACCEPTED);
    }

    #[Route('/{id}', name: 'process_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $this->messageBus->dispatch(
            new DeleteProcessCommand(
                id: $id
            )
        );

        // Vague response due to possible implementation wiht async.
        return new JsonResponse(['success' => 'success'], JsonResponse::HTTP_ACCEPTED);
    }

    #[Route('', name: 'process_list', methods: ['GET'])]
    public function list(?string $statusFilter): JsonResponse
    {
        // Get all is handled synchronously.
        $envelope = $this->messageBus->dispatch(new GetAllProcessesQuery(
            statusFilter: $statusFilter ?? null
        ));
        $stamp = $envelope->last(HandledStamp::class);
        $process = $stamp?->getResult() ?? [];

        return new JsonResponse([
            'success' => 'success',
            'process' => $process,
        ], JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'process_get_one', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function getOne(int $id): JsonResponse
    {
        // Get one is handled synchronously.
        $envelope = $this->messageBus->dispatch(new GetProcessByIdQuery(id: $id));
        $stamp = $envelope->last(HandledStamp::class);
        $process = $stamp?->getResult();

        return new JsonResponse([
            'success' => 'success',
            'process' => $process,
        ], JsonResponse::HTTP_OK);
    }
}
