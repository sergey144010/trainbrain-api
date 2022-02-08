<?php

namespace App\Entities;

class Definition
{
    public function __construct(private int $id, private string $name, private string $definition)
    {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function definition(): string
    {
        return $this->definition;
    }
}