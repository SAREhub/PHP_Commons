<?php

use PHPUnit\Framework\TestCase;
use SAREhub\Commons\Misc\TimeProvider;

class TimeProviderTest extends TestCase
{

    /**
     * @var  TimeProvider
     */
    private $timeProvider;

    public function setUp()
    {
        $this->timeProvider = new TimeProvider();
    }

    public function testFreezeTimeForCurrentTime()
    {
        $frozenTime = $this->timeProvider->freezeTime();
        $this->assertTrue($this->timeProvider->hasFrezenTime());
        $this->assertEquals($frozenTime, $this->timeProvider->now());
    }

    public function testFrezeTimeForCustomTime()
    {
        $customTime = 100;
        $frozenTime = $this->timeProvider->freezeTime($customTime);
        $this->assertEquals($customTime, $frozenTime);
        $this->assertEquals($frozenTime, $this->timeProvider->now());
    }

    public function testUnfrezeTime()
    {
        $this->timeProvider->freezeTime();
        $this->timeProvider->unfreezeTime();
        $this->assertFalse($this->timeProvider->hasFrezenTime());
    }
}
