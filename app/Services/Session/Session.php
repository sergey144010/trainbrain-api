<?php

namespace App\Services\Session;

class Session
{
    public const SESSION_ID = 'session_id';

    private SessionId $sessionId;
    private SessionProvider $sessionProvider;
    private SessionSchema $sessionSchema;

    public function __construct(
        SessionProvider $sessionProvider,
        SessionId $sessionId = null
    ) {
        if (isset($sessionId)) {
            $this->sessionId = $sessionId;
        }

        $this->sessionProvider = $sessionProvider;
    }

    /*
    public static function create(SessionId $sessionId = null)
    {
        return new self(
            new SessionProvider(),
            $sessionId
        );
    }
    */

    /**
     * @return SessionSchema
     * @throws SessionNotFoundException
     */
    private function findSession(): SessionSchema
    {
        return $this->sessionProvider->findBySessionId($this->sessionId);
    }

    private function createSession(): SessionSchema
    {
        $sessionSchema = $this->sessionProvider->createSession();
        $this->sessionProvider->sessionSchemaToStorage($sessionSchema);
        return $sessionSchema;
    }

    public function make(): void
    {
        if (isset($this->sessionId)) {
            try {
                $this->sessionSchema = $this->findSession();
            } catch (SessionNotFoundException) {
                $this->sessionSchema = $this->createSession();
            }
        } else {
            $this->sessionSchema = $this->createSession();
        }
    }

    public function toJson(): string
    {
        if (! isset($this->sessionSchema)) {
            $this->make();
        }

        /** @var string $string */
        $string = json_encode($this->sessionSchema->toArray());
        return $string;
    }

    public function sessionSchema(): SessionSchema
    {
        if (isset($this->sessionSchema)) {
            return $this->sessionSchema;
        }

        throw new \RuntimeException('SessionSchema not defined');
    }

    /**
     * @return Array<string, mixed>
     */
    public function toArray(): array
    {
        if (! isset($this->sessionSchema)) {
            $this->make();
        }

        return $this->sessionSchema->toArray();
    }

    public function decideWrong(int $questionId): void
    {
        if (! isset($this->sessionSchema)) {
            throw new \RuntimeException('SessionSchema not defined');
        }

        $this->sessionSchema->question($questionId)->decideWrong();
    }

    public function decideRight(int $questionId): void
    {
        if (! isset($this->sessionSchema)) {
            throw new \RuntimeException('SessionSchema not defined');
        }

        $this->sessionSchema->question($questionId)->decideRight();
    }

    public function currentQuestion(int $questionId): void
    {
        if (! isset($this->sessionSchema)) {
            throw new \RuntimeException('SessionSchema not defined');
        }

        array_map(function ($questionId) {
            $this->sessionSchema->question($questionId)->notCurrent();
        }, $this->sessionSchema->questionsKeys());

        $this->sessionSchema->question($questionId)->current();
    }
}
