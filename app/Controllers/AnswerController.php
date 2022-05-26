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
use Symfony\Component\HttpFoundation\Response;

class AnswerController
{
    /**
     * @param Array<string, string> $options
     * @return void
     * @throws \App\Services\Session\SessionIdException
     * @throws \App\Services\Session\SessionNotFoundException
     */
    public function handle(array $options): void
    {
        $session = (new Session(
            new SessionProvider(
                new SessionRepository((new RedisClient())->redis()),
                new QuestionProvider(new DefinitionProvider(new DefinitionRepository()))
            ),
            isset($options['appSessionId']) ? new SessionId($options['appSessionId']) : null
        ));
        $session->findSession();

        $session->decideBy((int)$options['appQuestionId'], (int)$options['appAnswerId']);

        try {
            //TODO this has bug
            $session->decideBy((int)$options['appQuestionId'], (int)$options['appAnswerId']);
        } catch (\RuntimeException) {
            JsonResponse::fromJsonString(
                $session->withoutTrueId()->toJson(),
                Response::HTTP_OK,
                ['Access-Control-Allow-Origin' => '*']
            )->send();

            return;
        }

        $session->sessionSchemaToStorage();
        JsonResponse::fromJsonString(
            $session->withoutTrueId()->toJson(),
            Response::HTTP_OK,
            ['Access-Control-Allow-Origin' => '*']
        )->send();
    }
}
