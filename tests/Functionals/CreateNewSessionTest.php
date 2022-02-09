<?php

use App\Services\Definition\DefinitionProvider;
use App\Services\Definition\DefinitionRepositoryInterface;
use App\Services\Question\QuestionProvider;
use PHPUnit\Framework\TestCase;
use App\Services\Session\Session;
use App\Services\Session\SessionProvider;
use App\Services\Session\SessionRepositoryInterface;

class CreateNewSessionTest extends TestCase
{
    public const SESSION_ID_KEY = 'session_id';
    public const QUESTIONS_KEY = 'questions';


    private function newSession(): Session
    {
        return new Session(
            new SessionProvider(
                $this->createMock(SessionRepositoryInterface::class),
                $this->createMock(QuestionProvider::class)
            )
        );
    }

    public function testToJsonIsJson()
    {
        $newSession = $this->newSession();

        self::assertJson($newSession->toJson());
    }

    public function testToJsonHaveSessionId()
    {
        $newSession = $this->newSession();
        $session = json_decode($newSession->toJson(), true);

        self::assertArrayHasKey(self::SESSION_ID_KEY, $session);
    }

    public function testToJsonSessionIdNotEmpty()
    {
        $newSession = $this->newSession();
        $session = json_decode($newSession->toJson(), true);

        self::assertNotEmpty($session[self::SESSION_ID_KEY]);
    }

    public function testDifferentSessionId()
    {
        $session_1 = $this->newSession();
        $session_id_1 = json_decode($session_1->toJson(), true)[self::SESSION_ID_KEY];

        $session_2 = $this->newSession();
        $session_id_2 = json_decode($session_2->toJson(), true)[self::SESSION_ID_KEY];

        self::assertNotEquals($session_id_1, $session_id_2);
    }

    private function newSessionForCreateSession(): Session
    {
        $data = [
            [
                'id' => 1,
                'name' => 'array_change_key_case',
                'definition' => 'Changes the case of all keys in an array'
            ],
            [
                'id' => 2,
                'name' => 'array_chunk',
                'definition' => 'Split an array into chunks'
            ],
            [
                'id' => 3,
                'name' => 'array_column',
                'definition' => 'Return the values from a single column in the input array'
            ],
            [
                'id' => 4,
                'name' => 'array_combine',
                'definition' => 'Creates an array by using one array for keys and another for its values'
            ],
            [
                'id' => 5,
                'name' => 'array_count_values',
                'definition' => 'Counts all the values of an array'
            ],
        ];

        $definitionRepository = $this->createMock(DefinitionRepositoryInterface::class);
        $definitionRepository->expects($this->once())
            ->method('definitionCollection')
            ->will($this->returnValue($data));

        return new Session(
            new SessionProvider(
                $this->createMock(SessionRepositoryInterface::class),
                new QuestionProvider(new DefinitionProvider($definitionRepository))
            )
        );
    }

    public function testNewSessionFromData()
    {
        $session = $this->newSessionForCreateSession();
        $session->make();
        $sessionSchema = $session->sessionSchema()->toArray();

        self::assertArrayHasKey(self::SESSION_ID_KEY, $sessionSchema);
        self::assertArrayHasKey(self::QUESTIONS_KEY, $sessionSchema);
        self::assertCount(2, $sessionSchema[self::QUESTIONS_KEY]);
        self::assertArrayHasKey('question', $sessionSchema[self::QUESTIONS_KEY][0]);
        self::assertArrayHasKey('answers', $sessionSchema[self::QUESTIONS_KEY][0]);
        self::assertCount(4, $sessionSchema[self::QUESTIONS_KEY][0]['answers']);
        self::assertArrayHasKey('true_id', $sessionSchema[self::QUESTIONS_KEY][0]);
        self::assertArrayHasKey('status', $sessionSchema[self::QUESTIONS_KEY][0]);
        self::assertArrayHasKey('current', $sessionSchema[self::QUESTIONS_KEY][0]);

        self::assertCount(1, $sessionSchema[self::QUESTIONS_KEY][1]['answers']);
    }
}
