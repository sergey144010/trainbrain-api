<?php

use App\Services\Question\QuestionProvider;
use App\Services\Session\SessionId;
use App\Services\Session\SessionProvider as SessionProviderAlias;
use App\Services\Session\SessionRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CreateNewSessionFromCurrentDataTest extends TestCase
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

    public function testFromCurrentString()
    {
        $stdClass = json_decode(json: $this->sessionStringSaved, flags: JSON_THROW_ON_ERROR);
        $sessionId = new SessionId($stdClass->session_id);
        $questionCollection = [];
        foreach ($stdClass->questions as $question) {
            $questionCollection[] = \App\Services\Question\QuestionFiller::getQuestionFromData($question);
        }

        /** One */
        self::assertEquals(
            "Return the values from a single column in the input array",
            $questionCollection[0]->state()['question']
        );
        self::assertEquals(
            2,
            $questionCollection[0]->state()['true_id']
        );
        self::assertEquals(
            1,
            $questionCollection[0]->state()['status']
        );
        self::assertEquals(
            false,
            $questionCollection[0]->state()['current']
        );
        self::assertEquals(
            'array_chunk',
            $questionCollection[0]->state()['answers'][0]
        );
        self::assertEquals(
            'array_chunk',
            $questionCollection[0]->state()['answers'][0]
        );
        self::assertEquals(
            'array_change_key_case',
            $questionCollection[0]->state()['answers'][1]
        );
        self::assertEquals(
            'array_column',
            $questionCollection[0]->state()['answers'][2]
        );
        self::assertEquals(
            'array_combine',
            $questionCollection[0]->state()['answers'][3]
        );

        /** Two */
        self::assertEquals(
            "Changes the case of all keys in an array",
            $questionCollection[1]->state()['question']
        );
        self::assertEquals(
            1,
            $questionCollection[1]->state()['true_id']
        );
        self::assertEquals(
            null,
            $questionCollection[1]->state()['status']
        );
        self::assertEquals(
            true,
            $questionCollection[1]->state()['current']
        );
        self::assertEquals(
            'array_chunk',
            $questionCollection[1]->state()['answers'][0]
        );
        self::assertEquals(
            'array_chunk',
            $questionCollection[1]->state()['answers'][0]
        );
        self::assertEquals(
            'array_change_key_case',
            $questionCollection[1]->state()['answers'][1]
        );
        self::assertEquals(
            'array_column',
            $questionCollection[1]->state()['answers'][2]
        );
        self::assertEquals(
            'array_combine',
            $questionCollection[1]->state()['answers'][3]
        );

        /** Three */
        self::assertEquals(
            "Creates an array by using one array for keys and another for its values",
            $questionCollection[2]->state()['question']
        );
        self::assertEquals(
            3,
            $questionCollection[2]->state()['true_id']
        );
        self::assertEquals(
            null,
            $questionCollection[2]->state()['status']
        );
        self::assertEquals(
            false,
            $questionCollection[2]->state()['current']
        );
        self::assertEquals(
            'array_chunk',
            $questionCollection[2]->state()['answers'][0]
        );
        self::assertEquals(
            'array_chunk',
            $questionCollection[2]->state()['answers'][0]
        );
        self::assertEquals(
            'array_change_key_case',
            $questionCollection[2]->state()['answers'][1]
        );
        self::assertEquals(
            'array_column',
            $questionCollection[2]->state()['answers'][2]
        );
        self::assertEquals(
            'array_combine',
            $questionCollection[2]->state()['answers'][3]
        );
    }

    public function testFromCurrentData()
    {
        $sessionId = new SessionId("16546%asDad324rf");

        $sessionRepository = $this->createMock(SessionRepositoryInterface::class);
        $sessionRepository->expects($this->once())
            ->method('findBySessionId')
            ->will($this->returnValue(json_decode(json: $this->sessionStringSaved, flags: JSON_THROW_ON_ERROR)));
        $sessionProvider = new SessionProviderAlias(
            $sessionRepository,
            $this->createMock(QuestionProvider::class),
        );

        $session = new App\Services\Session\Session($sessionProvider, $sessionId);
        $session->make();

        self::assertEquals("16546%asDad324rf", $session->sessionSchema()->toArray()['session_id']);
    }
}
