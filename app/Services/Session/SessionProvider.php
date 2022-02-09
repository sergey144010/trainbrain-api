<?php

namespace App\Services\Session;

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

    private function toSchema(array $data): SessionSchema
    {
        return new SessionSchema(
            new SessionId($data['session_id']),
            []
        );
    }
}