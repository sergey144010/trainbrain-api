<?php

namespace App\Services\Session;

interface SessionRepositoryInterface
{
    /**
     * @param SessionId $sessionId
     * @return Array<string, mixed>
     * @throws SessionNotFoundException
     */
    public function findBySessionId(SessionId $sessionId): array;
}
