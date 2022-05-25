<?php

namespace App;

use App\Connections\RedisClient;
use App\Repositories\DefinitionRepository;
use App\Repositories\SessionRepository;
use App\Services\Definition\DefinitionProvider;
use App\Services\Question\QuestionProvider;
use App\Services\Session\Session;
use App\Services\Session\SessionId;
use App\Services\Session\SessionProvider;
use Symfony\Component\HttpFoundation\JsonResponse;

class Application
{
    public function __construct()
    {
    }

    public function run(): void
    {
        JsonResponse::fromJsonString(
            (new Session(
                new SessionProvider(
                    new SessionRepository((new RedisClient())->redis()),
                    new QuestionProvider(new DefinitionProvider(new DefinitionRepository()))
                ),
                new SessionId('35f785f3eea1476')
            ))->toJson()
        )->send();
    }
}
