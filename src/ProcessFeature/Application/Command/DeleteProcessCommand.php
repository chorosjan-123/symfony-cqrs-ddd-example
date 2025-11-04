<?php

namespace App\ProcessFeature\Application\Command;

class DeleteProcessCommand
{
    public int $id;

    /**
     * DeleteProcessCommand constructor
     * @param int $id
     */
    public function __construct(int $id) {
        $this->id = $id;
    }
}