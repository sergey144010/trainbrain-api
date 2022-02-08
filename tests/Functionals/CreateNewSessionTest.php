<?php
use PHPUnit\Framework\TestCase;
use App\Services\Session\Session;
use App\Services\Session\SessionProvider;
use App\Services\Session\SessionRepositoryInterface;

class CreateNewSessionTest extends TestCase
{
    public const SESSION_ID_KEY = 'session_id';

    private function newSession(): Session
    {
        return new Session(
            new SessionProvider(
                $this->createMock(SessionRepositoryInterface::class)
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
}
