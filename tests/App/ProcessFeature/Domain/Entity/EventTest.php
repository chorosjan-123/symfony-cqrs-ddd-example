<?php

namespace App\Tests\App\ProcessFeature\Domain\Entity;

use App\ProcessFeature\Domain\Entity\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testConstructorSetsCreatedAt()
    {
        $event = new Event();

        $this->assertInstanceOf(\DateTime::class, $event->getCreatedAt());
    }

    public function testGettersAndSetters()
    {
        $event = new Event();

        $event->setId(1)
            ->setProcessId(42)
            ->setProcessTitle('Test Process')
            ->setAction('created')
            ->setCreatedAt(new \DateTime('2025-01-01 00:00:00'));

        $this->assertEquals(1, $event->getId());
        $this->assertEquals(42, $event->getProcessId());
        $this->assertEquals('Test Process', $event->getProcessTitle());
        $this->assertEquals('created', $event->getAction());
        $this->assertEquals('2025-01-01 00:00:00', $event->getCreatedAt()->format('Y-m-d H:i:s'));
    }
}