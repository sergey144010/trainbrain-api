<?php

namespace App\Services\Session;

use App\Entities\Question;

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
        /** @var Question $item */
        foreach ($this->questionsCollection as $item) {
            $list[] = $item->state();
        }

        return $list;
    }
}