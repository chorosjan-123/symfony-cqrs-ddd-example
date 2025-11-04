<?php

namespace App\Tests\Integration\Repository;

use App\ProcessFeature\Domain\Entity\Event;
use App\ProcessFeature\Infrastructure\Repository\EventRepository;
use App\Tests\Utils\DatabaseTestCase;

class EventRepositoryTest extends DatabaseTestCase
{
    private EventRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = EventRepositoryTest::getContainer()->get(EventRepository::class);
    }

    public function testItSavesEvent(): void
    {
        $event = new Event();
        $event->setProcessId(42)
            ->setProcessTitle('Test Process')
            ->setAction('created');

        $this->repository->save($event);

        $found = $this->repository->find($event->getId());
        $this->assertNotNull($found);
        $this->assertSame(42, $found->getProcessId());
        $this->assertSame('Test Process', $found->getProcessTitle());
        $this->assertSame('created', $found->getAction());
    }

    public function testItFindsLastAsArray(): void
    {
        // Insert multiple events
        for ($i = 1; $i <= 3; $i++) {
            $event = new Event();
            $event->setProcessId($i)
                ->setProcessTitle("Process {$i}")
                ->setAction('updated');
            $this->repository->save($event);
        }

        $result = $this->repository->findLastAsArray();

        $this->assertIsArray($result);
        $this->assertCount(3, $result);

        // Ensure order is descending by createdAt
        $this->assertGreaterThanOrEqual(
            $result[1]['createdAt'],
            $result[0]['createdAt']
        );

        $this->assertArrayHasKey('processId', $result[0]);
        $this->assertArrayHasKey('processTitle', $result[0]);
        $this->assertArrayHasKey('action', $result[0]);
    }
}
