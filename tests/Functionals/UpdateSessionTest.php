<?php

use App\Services\Session\SessionId;
use App\Services\Session\SessionProvider;
use App\Services\Session\SessionRepositoryInterface;
use PHPUnit\Framework\TestCase;
use App\Services\Question\QuestionProvider;
use App\Services\Session\Session;

class UpdateSessionTest extends TestCase
{
    private string $sessionStringSaved = '{
        "session_id": "16546%asDad324rf",
        "questions": [
            {
                "question": "Return the values from a single column in the input array",
                "answers": ["array_chunk", "array_change_key_case", "array_column", "array_combine"],
                "true_id": 2,
                "status": 1,
                "current": false
            },
            {
                "question": "Changes the case of all keys in an array",
                "answers": ["array_chunk", "array_change_key_case", "array_column", "array_combine"],
                "true_id": 1,
                "status": null,
                "current": true
            },
            {
                "question": "Creates an array by using one array for keys and another for its values",
                "answers": ["array_chunk", "array_change_key_case", "array_column", "array_combine"],
                "true_id": 3,
                "status": null,
                "current": false
            }
        ]}';

    public function testUpdateData()
    {
        $sessionId = new SessionId("16546%asDad324rf");

        $sessionRepository = $this->createMock(SessionRepositoryInterface::class);
        $sessionRepository->expects($this->once())
            ->method('findBySessionId')
            ->will($this->returnValue(json_decode(json: $this->sessionStringSaved, flags: JSON_THROW_ON_ERROR)));
        $sessionProvider = new SessionProvider(
            $sessionRepository,
            $this->createMock(QuestionProvider::class),
        );

        $session = new Session($sessionProvider, $sessionId);
        $session->make();

        self::assertEquals("16546%asDad324rf", $session->toArray()['session_id']);

        self::assertEquals(null, $session->toArray()['questions'][1]['status']);
        $session->decideRight(1);
        self::assertEquals(1, $session->toArray()['questions'][1]['status']);

        self::assertEquals(false, $session->toArray()['questions'][2]['current']);
        $session->currentQuestion(2);
        self::assertEquals(true, $session->toArray()['questions'][2]['current']);
        self::assertEquals(false, $session->toArray()['questions'][1]['current']);
    }
}