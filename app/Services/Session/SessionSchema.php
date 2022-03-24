<?php

namespace App\Services\Session;

use App\Entities\Question;

class SessionSchema
{
    /**
     * @param SessionId $sessionId
     * @param Iterable<Question> $questionsCollection
     */
    public function __construct(
        private SessionId $sessionId,
        private iterable $questionsCollection
    ) {
    }

    /**
     * @return Array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'session_id' => $this->sessionId->value(),
            'questions' => $this->questionsCollectionToArray()
        ];
    }

    /**
     * @return Array<Array<string,mixed>>
     */
    private function questionsCollectionToArray(): array
    {
        $list = [];
        /** @var Question $question */
        foreach ($this->questionsCollection as $question) {
            $list[] = $question->state();
        }

        return $list;
    }
}
