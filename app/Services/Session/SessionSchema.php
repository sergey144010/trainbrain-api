<?php

namespace App\Services\Session;

use App\Entities\Question;

class SessionSchema
{
    /**
     * @param SessionId $sessionId
     * @param Array<Question> $questionsCollection
     */
    public function __construct(
        private SessionId $sessionId,
        private array $questionsCollection
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

    public function question(int $questionId): Question
    {
        if (! isset($this->questionsCollection[$questionId])) {
            throw new \RuntimeException('Question not defined');
        }

        return $this->questionsCollection[$questionId];
    }

    public function countQuestions(): int
    {
        return count($this->questionsCollection);
    }

    /**
     * @return Array<int, int>
     */
    public function questionsKeys(): array
    {
        return array_keys($this->questionsCollection);
    }
}
