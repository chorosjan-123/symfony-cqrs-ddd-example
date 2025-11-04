<?php

namespace App\ProcessFeature\Application\Handler;

use App\ProcessFeature\Application\Command\DeleteProcessCommand;
use App\ProcessFeature\Domain\Repository\ProcessRepositoryInterface;
use RuntimeException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class DeleteProcessHandler
{
    private ProcessRepositoryInterface $processRepository;

    public function __construct(ProcessRepositoryInterface $processRepository)
    {
        $this->processRepository = $processRepository;
    }

    public function __invoke(DeleteProcessCommand $command): void
    {
        $existingProcess = $this->processRepository->find($command->id);
        if (!$existingProcess) {
            throw new RuntimeException('Process does not exists.');
        }

        $this->processRepository->delete($existingProcess);
    }
}