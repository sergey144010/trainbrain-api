<?php

namespace App\Repositories;

use App\Services\Session\SessionId;
use App\Services\Session\SessionNotFoundException;
use App\Services\Session\SessionRepositoryInterface;
use App\Services\Session\SessionSchema;

class SessionRepository implements SessionRepositoryInterface
{
    public function findBySessionId(SessionId $sessionId): \stdClass
    {
        throw new SessionNotFoundException();
    }

    public function toStorage(SessionSchema $sessionSchema): void
    {
        $redis = new \Redis();
        $redis->connect('redis', 6379);
        $schema = $sessionSchema->toArray();
        $redis->set($schema['session_id'], json_encode($schema));
    }
}