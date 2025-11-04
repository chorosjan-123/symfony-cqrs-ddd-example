<?php

namespace App\ProcessFeature\Application\Handler;

use App\ProcessFeature\Application\Command\ChangeProcessStatusCommand;
use App\ProcessFeature\Domain\Repository\ProcessRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use RuntimeException;

#[AsMessageHandler]
class ChangeProcessStatusHandler
{
    private ProcessRepositoryInterface $processRepository;

    public function __construct(ProcessRepositoryInterface $processRepository)
    {
        $this->processRepository = $processRepository;
    }

    public function __invoke(ChangeProcessStatusCommand $command): void
    {
        $process = $this->processRepository->find($command->id);
        if (!$process) {
            throw new RuntimeException('Process not found');
        }

        $process->setStatus($command->status);

        $this->processRepository->save($process);
    }
}