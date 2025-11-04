<?php
namespace App\ProcessFeature\Domain\ValueObject;

enum ProcessStatus: string
{
    case todo = 'todo';
    case processing = 'processing';
    case in_progress = 'in_progress';
    case done = 'done';
    case cancelled = 'cancelled';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'name');
    }
}