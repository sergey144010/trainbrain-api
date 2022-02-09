<?php

namespace App\Entities;

class Question
{
    public const STATUS_DID_NOT_DECIDED = null;
    public const STATUS_DECIDED_WRONG = 0;
    public const STATUS_DECIDED_RIGHT = 1;

    public const STATE_KEY_QUESTION = 'question';
    public const STATE_KEY_ANSWERS = 'answers';
    public const STATE_KEY_TRUE_ID = 'true_id';
    public const STATE_KEY_STATUS = 'status';
    public const STATE_KEY_CURRENT = 'current';

    /** @var Array<Definition> $definitionCollection */
    private array $definitionCollection = [];
    private array $state = [
        self::STATE_KEY_QUESTION => '',
        self::STATE_KEY_ANSWERS => [],
        self::STATE_KEY_TRUE_ID => null,
    ];
    private bool|null $status = null;
    private bool $current = false;


    public function addDefinition(Definition $definition): void
    {
        $this->definitionCollection[] = $definition;
    }

    public function make(): void
    {
        shuffle($this->definitionCollection);
        $this->initState(array_rand($this->definitionCollection));
    }

    private function initState(int $key): void
    {
        $this->state[self::STATE_KEY_QUESTION] = $this->definitionCollection[$key]->definition();
        $this->state[self::STATE_KEY_TRUE_ID] = $key;
        $this->state[self::STATE_KEY_ANSWERS] = array_map(function ($definition) {
            return $definition->name();
        }, $this->definitionCollection);
    }

    public function state(): array
    {
        if (empty($this->state[self::STATE_KEY_QUESTION])) {
            $this->make();
        }

        return [
            ...$this->state,
            self::STATE_KEY_STATUS => $this->status,
            self::STATE_KEY_CURRENT => $this->current,
        ];
    }

    public function hasDefinitions(): bool
    {
        return ! empty($this->definitionCollection) ? true : false;
    }
}