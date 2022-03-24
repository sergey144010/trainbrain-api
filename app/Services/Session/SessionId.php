<?php

namespace App\Services\Session;

class SessionId
{
    public const LENGTH = 15;

    private string $sessionId;

    public function __construct(string $sessionId)
    {
        $sessionId = trim($sessionId);

        if (empty($sessionId)) {
            throw new SessionIdException('SessionId empty');
        }

        if (strlen($sessionId) < self::LENGTH) {
            throw new SessionIdException('SessionId less 15 number');
        }

        $this->sessionId = $sessionId;
    }

    public function value(): string
    {
        return $this->sessionId;
    }
}
