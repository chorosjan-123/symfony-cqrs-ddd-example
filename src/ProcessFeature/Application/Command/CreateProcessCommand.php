<?php

namespace App\ProcessFeature\Application\Command;

class CreateProcessCommand
{
    public string $title;
    public ?string $description;
    public string $status;

    /**
     * CreateProcessCommand constructor
     * @param string $title
     * @param string|null $description
     * @param string|null $status
     */
    public function __construct(string $title, ?string $description, string $status) {
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
    }
}