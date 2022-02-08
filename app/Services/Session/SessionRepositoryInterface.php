<?php

namespace App\Services\Session;

interface SessionRepositoryInterface
{
    /**
     * @param SessionId $sessionId
     * @return array
     * @throws SessionNotFoundException
     */
    public function findBySessionId(SessionId $sessionId): array;
}