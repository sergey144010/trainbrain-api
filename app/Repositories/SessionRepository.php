<?php

namespace App\Repositories;

use App\Services\Session\SessionId;
use App\Services\Session\SessionNotFoundException;
use App\Services\Session\SessionRepositoryInterface;
use App\Services\Session\SessionSchema;

class SessionRepository implements SessionRepositoryInterface
{
    public function __construct(private \Redis $redis)
    {
    }

    public function findBySessionId(SessionId $sessionId): \stdClass
    {
        $response = $this->redis->get($sessionId->value());
        if ($response === false) {
            throw new SessionNotFoundException();
        }

        return json_decode($response);
    }

    public function toStorage(SessionSchema $sessionSchema): void
    {
        $schema = $sessionSchema->toArray();
        $this->redis->set($schema['session_id'], json_encode($schema));
    }
}
