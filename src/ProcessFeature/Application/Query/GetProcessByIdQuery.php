<?php

namespace App\ProcessFeature\Application\Query;

class GetProcessByIdQuery 
{
    public int $id;

    public function __construct(int $id) {
        $this->id = $id;
    }
}