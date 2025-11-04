<?php

namespace App\ProcessFeature\Application\Handler;

use App\ProcessFeature\Application\Command\CreateProcessCommand;
use App\ProcessFeature\Domain\Entity\Process;
use App\ProcessFeature\Domain\Repository\ProcessRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

#[AsMessageHandler]
class CreateProcessHandler
{
    private ProcessRepositoryInterface $processRepository;

    public function __construct(ProcessRepositoryInterface $processRepository)
    {
        $this->processRepository = $processRepository;
    }

    public function __invoke(CreateProcessCommand $command): void
    {
        $existingProcess = $this->processRepository->findByTitle($command->title);
        if ($existingProcess !== null) {
            throw new ConflictHttpException('A process with this title already exists.');
        }

        $process = new Process();
        $process->setTitle($command->title);
        $process->setDescription($command->description);
        $process->setStatus($command->status);

        $this->processRepository->save($process);
    }
}
