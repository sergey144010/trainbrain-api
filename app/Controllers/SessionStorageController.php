<?php

namespace App\Controllers;

use App\Connections\RedisClient;
use App\Repositories\DefinitionRepository;
use App\Repositories\SessionRepository;
use App\Services\Definition\DefinitionProvider;
use App\Services\Question\QuestionProvider;
use App\Services\Session\Session;
use App\Services\Session\SessionId;
use App\Services\Session\SessionProvider;
use Symfony\Component\HttpFoundation\JsonResponse;

class SessionStorageController
{
    /**
     * @param Array<string, string> $options
     * @return void
     * @throws \App\Services\Session\SessionIdException
     */
    public function handle(array $options): void
    {
        JsonResponse::fromJsonString(
            (new Session(
                new SessionProvider(
                    new SessionRepository((new RedisClient())->redis()),
                    new QuestionProvider(new DefinitionProvider(new DefinitionRepository()))
                ),
                isset($options['appSessionId']) ? new SessionId($options['appSessionId']) : null
            ))->toJson()
        )->send();
    }
}
