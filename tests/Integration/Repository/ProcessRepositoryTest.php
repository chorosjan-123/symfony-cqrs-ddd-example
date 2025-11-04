<?php

namespace App\Tests\Integration\Repository;

use App\ProcessFeature\Domain\Entity\Process;
use App\ProcessFeature\Domain\ValueObject\ProcessStatus;
use App\ProcessFeature\Infrastructure\Repository\ProcessRepository;
use App\Tests\Utils\DatabaseTestCase;

class ProcessRepositoryTest extends DatabaseTestCase
{
    private ProcessRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = ProcessRepositoryTest::getContainer()->get(ProcessRepository::class);
    }

    public function test_it_persists_and_finds_process(): void
    {
        $process = (new Process())
            ->setTitle('Integration test process')
            ->setDescription('Testing repository persistence')
            ->setStatus(ProcessStatus::todo);

        $this->entityManager->persist($process);
        $this->entityManager->flush();
        $this->entityManager->clear();

        $found = $this->repository->findOneBy(['title' => 'Integration test process']);
        $this->assertNotNull($found);
        $this->assertSame('Integration test process', $found->getTitle());
        $this->assertSame(ProcessStatus::todo, $found->getStatus());
    }

    public function test_it_updates_process_status(): void
    {
        $process = (new Process())
            ->setTitle('Status test')
            ->setStatus(ProcessStatus::in_progress);
        $this->entityManager->persist($process);
        $this->entityManager->flush();

        $process->setStatus(ProcessStatus::done);
        $this->entityManager->flush();

        $found = $this->repository->find($process->getId());
        $this->assertSame(ProcessStatus::done, $found->getStatus());
    }
}
