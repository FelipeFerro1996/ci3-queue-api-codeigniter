<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Task_dto
{
    public $title;
    public $description;
    public $priority;

    public function __construct(
        $title, 
        $description = null,
        $priority = 1
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->priority = $priority;
    }

    public function toArray()
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
        ];
    }
}