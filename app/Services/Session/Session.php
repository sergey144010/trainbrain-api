<?php

namespace App\Services\Session;

class Session
{
    public const SESSION_ID = 'session_id';

    private SessionId $sessionId;
    private SessionProvider $sessionProvider;
    private SessionSchema $sessionSchema;

    private bool $withoutTrueId = false;

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
     * @throws SessionNotFoundException
     */
    public function findSession(): void
    {
        if (! isset($this->sessionId)) {
            throw new \RuntimeException('SessionId not defined');
        }

        $this->sessionSchema = $this->sessionProvider->findBySessionId($this->sessionId);
    }

    private function createSession(): void
    {
        $this->sessionSchema = $this->sessionProvider->createSession();
        $this->sessionSchemaToStorage();
    }

    public function sessionSchemaToStorage(): void
    {
        $this->sessionProvider->sessionSchemaToStorage($this->sessionSchema);
    }

    public function make(): void
    {
        if (isset($this->sessionId)) {
            try {
                $this->findSession();
            } catch (SessionNotFoundException) {
                $this->createSession();
            }
        } else {
            $this->createSession();
        }
    }

    public function toJson(): string
    {
        /** @var string $string */
        $string = json_encode($this->toArray());
        return $string;
    }

    /**
     * @return Array<string, mixed>
     */
    public function toArray(): array
    {
        if (! isset($this->sessionSchema)) {
            $this->make();
        }

        $response = $this->sessionSchema->toArray();

        if ($this->withoutTrueId) {
            $response['questions'] = array_map(static function (array $question) {
                unset($question['true_id']);
                return $question;
            }, $response['questions']);
        }

        return $response;
    }

    public function sessionSchema(): SessionSchema
    {
        if (isset($this->sessionSchema)) {
            return $this->sessionSchema;
        }

        throw new \RuntimeException('SessionSchema not defined');
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

    public function decideBy(int $questionId, int $trueId): void
    {
        $this->sessionSchema->question($questionId)->decideBy($trueId);
    }

    public function withoutTrueId(): self
    {
        $this->withoutTrueId = true;

        return $this;
    }
}
