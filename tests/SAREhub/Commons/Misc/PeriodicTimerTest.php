<?php

use PHPUnit\Framework\TestCase;
use SAREhub\Commons\Misc\PeriodicTimer;

class PeriodicTimerTest extends TestCase
{

    /**
     * @var PeriodicTimer
     */
    private $timer;

    private $callbackMock;

    public function testFirstCall()
    {
        $this->callbackMock->expects($this->once())->method('__invoke');
        $this->timer->update(2);
    }

    public function testUpdateWhenBeforeNextCall()
    {
        $this->timer->update(1);
        $this->callbackMock->expects($this->never())->method('__invoke');
        $this->timer->update(1);
    }

    public function testUpdateWhenAfterNextCall()
    {
        $this->timer->update(3);
        $this->callbackMock->expects($this->once())->method('__invoke');
        $this->timer->update(5);
    }

    public function testCallNow()
    {
        $this->callbackMock->expects($this->once())->method('__invoke');
        $this->timer->callNow();
    }

    protected function setUp()
    {
        $this->callbackMock = $this->createPartialMock(\stdClass::class, ['__invoke']);
        $this->timer = PeriodicTimer::every(2)->call($this->callbackMock);
    }
}
