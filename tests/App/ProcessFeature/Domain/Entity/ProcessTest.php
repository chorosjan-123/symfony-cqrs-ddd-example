<?php

namespace App\Tests\App\ProcessFeature\Domain\Entity;

use App\ProcessFeature\Domain\Entity\Process;
use App\ProcessFeature\Domain\ValueObject\ProcessStatus;
use PHPUnit\Framework\TestCase;

class ProcessTest extends TestCase
{
    public function testSetAndGetId(): void
    {
        $process = new Process();
        $process->setId(123);
        $this->assertSame(123, $process->getId());
    }

    public function testSetAndGetTitle(): void
    {
        $process = new Process();
        $process->setTitle('My Process');
        $this->assertSame('My Process', $process->getTitle());
    }

    public function testSetAndGetDescription(): void
    {
        $process = new Process();
        $process->setDescription('Some description');
        $this->assertSame('Some description', $process->getDescription());

        $process->setDescription(null);
        $this->assertNull($process->getDescription());
    }

    public function testSetAndGetStatus(): void
    {
        $process = new Process();
        $process->setStatus(ProcessStatus::todo);
        $this->assertSame(ProcessStatus::todo, $process->getStatus());

        $process->setStatus('in_progress');
        $this->assertSame(ProcessStatus::in_progress, $process->getStatus());
    }

    public function testCannotSetDoneDirectly(): void
    {
        $process = new Process();
        $process->setStatus(ProcessStatus::todo);

        $this->expectException(\DomainException::class);
        $process->setStatus(ProcessStatus::done);
    }

    public function testSetDoneAfterInProgress(): void
    {
        $process = new Process();
        $process->setStatus(ProcessStatus::in_progress);
        $process->setStatus(ProcessStatus::done);

        $this->assertSame(ProcessStatus::done, $process->getStatus());
    }

    public function testCreatedAtIsSetOnPrePersist(): void
    {
        $process = new Process();
        $this->assertNull($process->getCreatedAt());

        $process->setCreatedAtValue();
        $this->assertInstanceOf(\DateTime::class, $process->getCreatedAt());
    }

    public function testUpdatedAtIsSetOnPreUpdate(): void
    {
        $process = new Process();
        $this->assertNull($process->getUpdatedAt());

        $process->setUpdatedAtValue();
        $this->assertInstanceOf(\DateTime::class, $process->getUpdatedAt());
    }

    public function testIsProtectedReturnsTrueWhenDone(): void
    {
        $process = new Process();
        $process->setStatus(ProcessStatus::in_progress);
        $process->setStatus(ProcessStatus::done);
        $this->assertTrue($process->isProtected());
    }

    public function testIsProtectedReturnsFalseOtherwise(): void
    {
        $process = new Process();
        $process->setStatus(ProcessStatus::todo);
        $this->assertFalse($process->isProtected());

        $process->setStatus(ProcessStatus::in_progress);
        $this->assertFalse($process->isProtected());
    }
}
