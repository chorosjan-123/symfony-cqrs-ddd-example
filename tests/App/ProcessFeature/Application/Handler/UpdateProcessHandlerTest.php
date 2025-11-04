<?php

namespace App\Tests\App\ProcessFeature\Application\Handler;

use App\ProcessFeature\Application\Command\UpdateProcessCommand;
use App\ProcessFeature\Application\Handler\UpdateProcessHandler;
use App\ProcessFeature\Domain\Entity\Process;
use App\ProcessFeature\Domain\Repository\ProcessRepositoryInterface;
use App\ProcessFeature\Domain\ValueObject\ProcessStatus;
use App\Tests\Utils\DatabaseTestCase;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UpdateProcessHandlerTest extends DatabaseTestCase
{
    private $repository;
    private UpdateProcessHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(ProcessRepositoryInterface::class);
        $this->handler = new UpdateProcessHandler($this->repository);
    }

    public function test_it_throws_exception_if_process_not_found(): void
    {
        // Arrange
        $command = new UpdateProcessCommand(1, 'New Title', 'Updated Description', ProcessStatus::in_progress->value);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(ConflictHttpException::class);
        $this->expectExceptionMessage('Process to update does not exist.');

        // Act
        ($this->handler)($command);
    }

    public function test_it_throws_exception_if_title_conflicts_with_another_process(): void
    {
        // Arrange
        $existingProcess = (new Process())->setId(2)->setTitle('Duplicate Title');
        $processToUpdate = (new Process())->setId(1)->setTitle('Old Title');

        $command = new UpdateProcessCommand(1, 'Duplicate Title', 'Updated Description', ProcessStatus::todo->value);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($processToUpdate);

        $this->repository->expects($this->once())
            ->method('findByTitle')
            ->with('Duplicate Title')
            ->willReturn($existingProcess);

        $this->expectException(ConflictHttpException::class);
        $this->expectExceptionMessage('A process with this title already exists.');

        // Act
        ($this->handler)($command);
    }

    public function test_it_updates_process_and_saves_successfully(): void
    {
        // Arrange
        $process = (new Process())
            ->setId(1)
            ->setTitle('Original Title')
            ->setDescription('Old Description')
            ->setStatus(ProcessStatus::todo);

        $command = new UpdateProcessCommand(1, 'Updated Title', 'New Description', ProcessStatus::in_progress->value);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($process);

        $this->repository->expects($this->once())
            ->method('findByTitle')
            ->with('Updated Title')
            ->willReturn(null);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Process $updatedProcess) use ($command) {
                return $updatedProcess->getId() === 1
                    && $updatedProcess->getTitle() === $command->title
                    && $updatedProcess->getDescription() === $command->description
                    && $updatedProcess->getStatus()->value === $command->status;
            }));

        // Act
        ($this->handler)($command);
    }
}