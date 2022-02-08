<?php
use PHPUnit\Framework\TestCase;
use App\Services\Session\Session;
use App\Services\Session\SessionId;
use App\Services\Session\SessionNotFoundException;
use App\Services\Session\SessionProvider;
use App\Services\Session\SessionRepositoryInterface;

class TakeSessionTest extends TestCase
{
    public const SESSION_ID_KEY = 'session_id';
    public const SESSION_ID_VALUE = 'string-session-id';

    public function testTakeSession()
    {
        $newSession = new Session(
            new SessionProvider(
                $this->createMock(SessionRepositoryInterface::class)
            ),
            new SessionId(self::SESSION_ID_VALUE)
        );

        $response = [
            self::SESSION_ID_KEY => self::SESSION_ID_VALUE
        ];

        self::assertEquals($newSession->toJson(), json_encode($response));
    }

    public function testSessionNotFound()
    {
        $this->expectException(SessionNotFoundException::class);

        $repository = $this->createMock(SessionRepositoryInterface::class);
        $repository->expects($this->once())->method('findBySessionId')->will($this->throwException(new SessionNotFoundException()));

        $newSession = new Session(
            new SessionProvider($repository),
            new SessionId(self::SESSION_ID_VALUE)
        );
        $newSession->toJson();
    }
}