<?php
use PHPUnit\Framework\TestCase;
use App\Services\Session\Session;
use App\Services\Session\SessionId;


class SessionTest extends TestCase
{
    public function testCreateNewSession()
    {
        $newSession = new Session(new SessionId('string-session-id'));
    }
}
