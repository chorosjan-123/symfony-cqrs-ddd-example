<?php

namespace App\Tests\App\ProcessFeature\Application\Handler;


use App\ProcessFeature\Application\Command\DeleteProcessCommand;
use App\ProcessFeature\Application\Handler\DeleteProcessHandler;
use App\ProcessFeature\Domain\Entity\Process;
use App\ProcessFeature\Domain\Repository\ProcessRepositoryInterface;
use App\Tests\Utils\DatabaseTestCase;
use RuntimeException;

class DeleteProcessHandlerTest extends DatabaseTestCase
{
    private $repository;
    private DeleteProcessHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(ProcessRepositoryInterface::class);
        $this->handler = new DeleteProcessHandler($this->repository);
    }

    public function test_it_deletes_existing_process(): void
    {
        // Arrange
        $command = new DeleteProcessCommand(id: 123);
        $existingProcess = $this->createMock(Process::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(123)
            ->willReturn($existingProcess);

        $this->repository->expects($this->once())
            ->method('delete')
            ->with($existingProcess);

        // Act
        ($this->handler)($command);

        // Assert
        $this->assertTrue(true); // All expectations validated
    }

    public function test_it_throws_exception_when_process_does_not_exist(): void
    {
        // Arrange
        $command = new DeleteProcessCommand(id: 456);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(456)
            ->willReturn(null);

        $this->repository->expects($this->never())
            ->method('delete');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Process does not exists.');

        // Act
        ($this->handler)($command);
    }
}
