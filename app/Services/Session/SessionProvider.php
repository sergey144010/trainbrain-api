<?php

namespace App\Services\Session;

use App\Services\Question\QuestionFiller;
use App\Services\Question\QuestionProvider;

class SessionProvider
{
    public function __construct(
        private SessionRepositoryInterface $repository,
        private QuestionProvider $questionProvider,
    ) {
    }

    /**
     * @param SessionId $sessionId
     * @return SessionSchema
     * @throws SessionNotFoundException
     */
    public function findBySessionId(SessionId $sessionId): SessionSchema
    {
        return $this->toSchema(
            $this->repository->findBySessionId($sessionId)
        );
    }

    private function toSchema(\stdClass $data): SessionSchema
    {
        $questionCollection = [];
        foreach ($data->questions as $question) {
            $questionCollection[] = QuestionFiller::getQuestionFromData($question);
        }

        return new SessionSchema(
            new SessionId($data->session_id),
            $questionCollection
        );
    }

    public function createSession(): SessionSchema
    {
        return new SessionSchema(
            $this->generateSessionId(),
            $this->questionProvider->getCollection()
        );
    }

    private function generateSessionId(): SessionId
    {
        return new SessionId($this->generateRandomString());
    }

    private function generateRandomString(): string
    {
        return substr(str_shuffle(md5(microtime())), random_int(0, 16), SessionId::LENGTH);
    }

    public function sessionSchemaToStorage(SessionSchema $sessionSchema): void
    {
        $this->repository->toStorage($sessionSchema);
    }
}
