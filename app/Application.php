<?php

namespace App;

use App\Repositories\DefinitionRepository;
use App\Repositories\SessionRepository;
use App\Services\Definition\DefinitionProvider;
use App\Services\Question\QuestionProvider;
use App\Services\Session\Session;
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
                    new SessionRepository(),
                    new QuestionProvider(new DefinitionProvider(new DefinitionRepository()))
                )
            ))->toJson()
        )->send();
    }
}
