<?php

namespace App\Tests\App\ProcessFeature\Application\Handler;

use App\ProcessFeature\Application\Command\ChangeProcessStatusCommand;
use App\ProcessFeature\Application\Handler\ChangeProcessStatusHandler;
use App\ProcessFeature\Domain\Entity\Process;
use App\ProcessFeature\Domain\Repository\ProcessRepositoryInterface;
use App\ProcessFeature\Domain\ValueObject\ProcessStatus;
use App\Tests\Utils\DatabaseTestCase;
use RuntimeException;

class ChangeProcessStatusHandlerTest extends DatabaseTestCase
{
    private $repository;
    private ChangeProcessStatusHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(ProcessRepositoryInterface::class);
        $this->handler = new ChangeProcessStatusHandler($this->repository);
    }

    public function test_it_changes_process_status_and_saves(): void
    {
        // Arrange
        $process = $this->createMock(Process::class);
        $process->expects($this->once())
            ->method('setStatus')
            ->with(ProcessStatus::done->value);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(123)
            ->willReturn($process);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($process);

        $command = new ChangeProcessStatusCommand(123, ProcessStatus::done->value);

        // Act
        ($this->handler)($command);

        // Assert → verified by mocks
        $this->assertTrue(true);
    }

    public function test_it_throws_exception_if_process_not_found(): void
    {
        // Arrange
        $this->repository->expects($this->once())
            ->method('find')
            ->with(999)
            ->willReturn(null);

        $command = new ChangeProcessStatusCommand(999, ProcessStatus::in_progress->value);

        // Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Process not found');

        // Act
        ($this->handler)($command);
    }
}