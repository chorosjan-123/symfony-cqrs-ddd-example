<?php

namespace App\ProcessFeature\Application\Handler;

use App\ProcessFeature\Application\Command\UpdateProcessCommand;
use App\ProcessFeature\Domain\Repository\ProcessRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

#[AsMessageHandler]
class UpdateProcessHandler
{
    private ProcessRepositoryInterface $processRepository;

    public function __construct(ProcessRepositoryInterface $processRepository)
    {
        $this->processRepository = $processRepository;
    }

    public function __invoke(UpdateProcessCommand $command): void
    {


        $foundProcess = $this->processRepository->find($command->id);
        if (!$foundProcess) {
            throw new ConflictHttpException('Process to update does not exist.');
        }
        if (isset($command->title)) {
            $existingProcess = $this->processRepository->findByTitle($command->title);
            // If the ID is different but title is the same.
            if ($existingProcess !== null) {
                if ($existingProcess->getId() !== $foundProcess->getId()) {
                    throw new ConflictHttpException('A process with this title already exists.');
                }
            }
        }

        $foundProcess->setId($command->id);
        if ($command->title !== null) {
            $foundProcess->setTitle($command->title);
        }
        if ($command->description !== null) {
            $foundProcess->setDescription($command->description);
        }
        if ($command->status !== null) {
            $foundProcess->setStatus($command->status);
        }

        $this->processRepository->save($foundProcess);
    }
}