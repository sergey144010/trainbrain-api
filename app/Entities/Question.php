<?php

namespace App\Entities;

class Question
{
    public const DID_NOT_DECIDED = null;
    public const DECIDED_WRONG = 0;
    public const DECIDED_RIGHT = 1;

    /** @var Array<Definition> $definitionCollection */
    private array $definitionCollection = [];
    private array $state = [
        'question' => '',
        'answers' => [],
        'status' => null,
        'current' => false,
        'true_id' => null,
    ];

    public function addDefinition(Definition $definition): void
    {
        $this->definitionCollection[] = $definition;
    }

    private function make(): void
    {
        shuffle($this->definitionCollection);
        $this->initState(array_rand($this->definitionCollection));
    }

    private function initState(int $key): void
    {
        $this->state['question'] = $this->definitionCollection[$key]->definition();
        $this->state['true_id'] = $key;
        $this->state['answers'] = array_map(function ($definition) {
            return $definition->name();
        }, $this->definitionCollection);
    }

    public function state(): array
    {
        if (empty($this->state['question'])) {
            $this->make();
        }

        return $this->state;
    }
}