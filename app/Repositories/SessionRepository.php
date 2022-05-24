<?php

namespace App\Repositories;

use App\Services\Session\SessionId;
use App\Services\Session\SessionNotFoundException;
use App\Services\Session\SessionRepositoryInterface;

class SessionRepository implements SessionRepositoryInterface
{
    public function findBySessionId(SessionId $sessionId): \stdClass
    {
        throw new SessionNotFoundException();
    }

}