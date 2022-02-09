<?php
use PHPUnit\Framework\TestCase;
use App\Services\Session\Session;
use App\Services\Session\SessionId;
use App\Services\Session\SessionProvider;
use App\Services\Session\SessionRepositoryInterface;
use App\Services\Question\QuestionProvider;

class TakeSessionTest extends TestCase
{
    public const SESSION_ID_KEY = 'session_id';
    public const SESSION_ID_VALUE = 'string-session-id';
    public const QUESTIONS_KEY = 'questions';

    public function testTakeSession()
    {
        $repository = $this->createMock(SessionRepositoryInterface::class);
        $repository->expects($this->once())
        ->method('findBySessionId')
        ->will($this->returnValue([self::SESSION_ID_KEY => self::SESSION_ID_VALUE]));

        $newSession = new Session(
            new SessionProvider($repository, $this->createMock(QuestionProvider::class)),
            new SessionId(self::SESSION_ID_VALUE)
        );

        $response = [
            self::SESSION_ID_KEY => self::SESSION_ID_VALUE,
            self::QUESTIONS_KEY => [],
        ];

        self::assertEquals($newSession->toJson(), json_encode($response));
    }
}