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
        SessionId $sessionId = null)
    {
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
        return $this->sessionProvider->createSession();
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

        return json_encode($this->sessionSchema->toArray());
    }

    public function sessionSchema(): SessionSchema
    {
        if (isset($this->sessionSchema)) {
            return $this->sessionSchema;
        }

        throw new \RuntimeException('SessionSchema not defined');
    }
}