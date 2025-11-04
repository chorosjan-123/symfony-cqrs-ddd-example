<?php
namespace App\ProcessFeature\Domain\ValueObject;

enum ProcessEventAction: string
{
    case created = 'created';
    case updated = 'updated';
    case deleted = 'deleted';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'name');
    }
}