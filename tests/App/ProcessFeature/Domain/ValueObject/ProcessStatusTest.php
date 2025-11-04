<?php

namespace App\Tests\App\Domain\ValueObject;

use App\ProcessFeature\Domain\ValueObject\ProcessStatus;
use PHPUnit\Framework\TestCase;

class ProcessStatusTest extends TestCase
{
    public function testGetValuesReturnsAllNames(): void
    {
        $values = ProcessStatus::getValues();
        $this->assertEquals(['todo', 'processing', 'in_progress', 'done', 'cancelled'], $values);
    }

    public function testEnumCanBeCreatedFromString(): void
    {
        $status = ProcessStatus::from('todo');
        $this->assertSame(ProcessStatus::todo, $status);

        $status = ProcessStatus::from('processing');
        $this->assertSame(ProcessStatus::processing, $status);

        $status = ProcessStatus::from('in_progress');
        $this->assertSame(ProcessStatus::in_progress, $status);

        $status = ProcessStatus::from('done');
        $this->assertSame(ProcessStatus::done, $status);

        $status = ProcessStatus::from('cancelled');
        $this->assertSame(ProcessStatus::cancelled, $status);
    }

    public function testInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        ProcessStatus::from('invalid_status');
    }
}
