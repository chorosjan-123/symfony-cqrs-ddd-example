<?php

namespace App\Tests\App\ProcessFeature\Application\Handler;

use App\ProcessFeature\Application\Handler\GetProcessByIdHandler;
use App\ProcessFeature\Application\Query\GetProcessByIdQuery;
use App\ProcessFeature\Domain\Repository\ProcessRepositoryInterface;
use App\Tests\Utils\DatabaseTestCase;

class GetProcessByIdHandlerTest extends DatabaseTestCase
{
    private $repository;
    private GetProcessByIdHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(ProcessRepositoryInterface::class);
        $this->handler = new GetProcessByIdHandler($this->repository);
    }

    public function test_it_returns_process_as_array_when_found(): void
    {
        // Arrange
        $processId = 42;
        $expectedProcess = [
            'id' => $processId,
            'title' => 'Fix Bug #42',
            'description' => 'Fix the login issue',
            'status' => 'in_progress',
        ];

        $this->repository->expects($this->once())
            ->method('findByIdAsArray')
            ->with($processId)
            ->willReturn($expectedProcess);

        $query = new GetProcessByIdQuery($processId);

        // Act
        $result = ($this->handler)($query);

        // Assert
        $this->assertSame($expectedProcess, $result);
    }

    public function test_it_returns_null_when_process_not_found(): void
    {
        // Arrange
        $processId = 99;

        $this->repository->expects($this->once())
            ->method('findByIdAsArray')
            ->with($processId)
            ->willReturn(null);

        $query = new GetProcessByIdQuery($processId);

        // Act
        $result = ($this->handler)($query);

        // Assert
        $this->assertNull($result);
    }
}