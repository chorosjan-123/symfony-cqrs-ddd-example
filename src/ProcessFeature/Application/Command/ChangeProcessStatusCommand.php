<?php

namespace App\ProcessFeature\Application\Command;

class ChangeProcessStatusCommand
{
    public int $id;
    public string $status;

    /**
     * ChangeProcessStatusCommand constructor
     * @param int $id
     * @param string $status
     */
    public function __construct(int $id, string $status) {
        $this->id = $id;
        $this->status = $status;
    }
}