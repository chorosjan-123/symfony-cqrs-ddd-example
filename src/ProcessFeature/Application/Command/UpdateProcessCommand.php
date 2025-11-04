<?php

namespace App\ProcessFeature\Application\Command;

class UpdateProcessCommand
{
    public int $id;
    public ?string $title;
    public ?string $description;
    public ?string $status;

    /**
     * CreateProcessCommand constructor
     * @param int $id
     * @param string|null $title
     * @param string|null $description
     * @param string|null $status
     */
    public function __construct(int $id, ?string $title, ?string $description, ?string $status) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
    }
}