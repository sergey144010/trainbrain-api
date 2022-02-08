<?php

namespace App\Services\Session;

class Session
{
    public const SESSION_ID = 'session_id';

    private SessionId $sessionId;
    private SessionProvider $sessionProvider;

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

    public function toJson(): string
    {
        if (isset($this->sessionId)) {
            try {
                $session = $this->findSession();
            } catch (SessionNotFoundException) {
                $session = $this->createSession();
            }
        } else {
            $session = $this->createSession();
        }

        return json_encode($session->toArray());
    }
}