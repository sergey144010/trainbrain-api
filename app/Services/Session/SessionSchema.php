<?php

namespace App\Services\Session;

class SessionSchema
{
    public function __construct(
        private SessionId $sessionId,
        private QuestionsCollection $questionsCollection
    )
    {
    }

    public function toArray(): array
    {
        return [
            'session_id' => $this->sessionId->value(),
            'questions' => $this->questionsCollection->toArray()
        ];
    }
}