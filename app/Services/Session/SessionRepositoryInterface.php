<?php

namespace App\Services\Session;

interface SessionRepositoryInterface
{
    /**
     * @param SessionId $sessionId
     * @return \stdClass
     * @throws SessionNotFoundException
     */
    public function findBySessionId(SessionId $sessionId): \stdClass;
    public function toStorage(SessionSchema $sessionSchema): void;
}
