<?php

namespace App\Tests\App\ProcessFeature\Application\Handler;

use App\ProcessFeature\Application\Handler\GetAllProcessesHandler;
use App\ProcessFeature\Application\Query\GetAllProcessesQuery;
use App\ProcessFeature\Domain\Repository\ProcessRepositoryInterface;
use App\Tests\Utils\DatabaseTestCase;

class GetAllProcessesHandlerTest extends DatabaseTestCase
{
    private $repository;
    private GetAllProcessesHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(ProcessRepositoryInterface::class);
        $this->handler = new GetAllProcessesHandler($this->repository);
    }

    public function test_it_returns_all_process_as_array(): void
    {
        // Arrange
        $expectedProcesses = [
            ['id' => 1, 'title' => 'Process A', 'status' => 'pending'],
            ['id' => 2, 'title' => 'Process B', 'status' => 'in_progress'],
        ];

        $this->repository->expects($this->once())
            ->method('findAllAsArray')
            ->willReturn($expectedProcesses);

        $query = new GetAllProcessesQuery();

        // Act
        $result = ($this->handler)($query);

        // Assert
        $this->assertSame($expectedProcesses, $result);
    }

    public function test_it_returns_empty_array_when_no_process_found(): void
    {
        // Arrange
        $this->repository->expects($this->once())
            ->method('findAllAsArray')
            ->willReturn([]);

        $query = new GetAllProcessesQuery();

        // Act
        $result = ($this->handler)($query);

        // Assert
        $this->assertSame([], $result);
    }
}