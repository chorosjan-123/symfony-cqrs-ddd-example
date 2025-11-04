<?php

namespace App\Tests\App\ProcessFeature\Application\Handler;

use App\ProcessFeature\Application\Command\CreateProcessCommand;
use App\ProcessFeature\Application\Handler\CreateProcessHandler;
use App\ProcessFeature\Domain\Entity\Process;
use App\ProcessFeature\Domain\Repository\ProcessRepositoryInterface;
use App\ProcessFeature\Domain\ValueObject\ProcessStatus;
use App\Tests\Utils\DatabaseTestCase;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class CreateProcessHandlerTest extends DatabaseTestCase
{
    private $repository;
    private CreateProcessHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(ProcessRepositoryInterface::class);
        $this->handler = new CreateProcessHandler($this->repository);
    }

    public function test_it_creates_a_new_process_and_saves_it(): void
    {
        // Arrange
        $command = new CreateProcessCommand(
            title: 'New Process',
            description: 'Test description',
            status: ProcessStatus::todo->value
        );

        $this->repository->expects($this->once())
            ->method('findByTitle')
            ->with('New Process')
            ->willReturn(null);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Process $process) use ($command) {
                $this->assertSame($command->title, $process->getTitle());
                $this->assertSame($command->description, $process->getDescription());
                $this->assertSame($command->status, $process->getStatus()->value);
                return true;
            }));

        // Act
        ($this->handler)($command);

        // Assert → verified by mocks
        $this->assertTrue(true);
    }

    public function test_it_throws_exception_if_process_with_title_exists(): void
    {
        // Arrange
        $command = new CreateProcessCommand(
            title: 'Duplicate Process',
            description: 'Already exists',
            status: ProcessStatus::todo->value
        );

        $existingProcess = $this->createMock(Process::class);

        $this->repository->expects($this->once())
            ->method('findByTitle')
            ->with('Duplicate Process')
            ->willReturn($existingProcess);

        $this->expectException(ConflictHttpException::class);
        $this->expectExceptionMessage('A process with this title already exists.');

        // Act
        ($this->handler)($command);
    }
}