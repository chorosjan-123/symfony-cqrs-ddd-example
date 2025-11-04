<?php

namespace App\ProcessFeature\Application\Handler;

use App\ProcessFeature\Application\Query\GetAllProcessesQuery;
use App\ProcessFeature\Domain\Repository\ProcessRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;


#[AsMessageHandler]
class GetAllProcessesHandler
{
    private ProcessRepositoryInterface $processRepository;

    public function __construct(ProcessRepositoryInterface $processRepository)
    {
        $this->processRepository = $processRepository;
    }

    public function __invoke(GetAllProcessesQuery $query): ?array
    {
        return $this->processRepository->findAllAsArray($query->statusFilter);
    }
}