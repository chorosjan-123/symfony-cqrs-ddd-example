<?php

namespace App\Tests\App\Domain\ValueObject;

use App\ProcessFeature\Domain\ValueObject\ProcessEventAction;
use PHPUnit\Framework\TestCase;

class ProcessEventActionTest extends TestCase
{
    public function testGetValuesReturnsAllNames(): void
    {
        $values = ProcessEventAction::getValues();
        $this->assertEquals(['created', 'updated', 'deleted'], $values);
    }

    public function testEnumCanBeCreatedFromString(): void
    {
        $status = ProcessEventAction::from('created');
        $this->assertSame(ProcessEventAction::created, $status);

        $status = ProcessEventAction::from('updated');
        $this->assertSame(ProcessEventAction::updated, $status);

        $status = ProcessEventAction::from('deleted');
        $this->assertSame(ProcessEventAction::deleted, $status);
    }

    public function testInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        ProcessEventAction::from('invalid_status');
    }
}
