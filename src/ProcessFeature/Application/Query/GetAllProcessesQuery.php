<?php

namespace App\ProcessFeature\Application\Query;

class GetAllProcessesQuery 
{
    public ?string $statusFilter;

    public function __construct(?string $statusFilter = null) {
        $this->statusFilter = $statusFilter;
    }
}