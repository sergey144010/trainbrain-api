<?php

namespace App\Services\Session;

class SessionSchema
{
    public function __construct(
        private SessionId $sessionId,
        private iterable $questionsCollection
    )
    {
    }

    public function toArray(): array
    {
        return [
            'session_id' => $this->sessionId->value(),
            'questions' => $this->questionsCollectionToArray()
        ];
    }

    private function questionsCollectionToArray(): array
    {
        $list = [];
        foreach ($this->questionsCollection as $item) {
            $list[] = $item->toArray();
        }

        return $list;
    }
}