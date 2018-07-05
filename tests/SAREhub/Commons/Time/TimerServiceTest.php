<?php

namespace SAREhub\Commons\Time;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SAREhub\Commons\Misc\PeriodicTimer;
use SAREhub\Commons\Misc\TimeProvider;


class TimerServiceTest extends TestCase
{

    use MockeryPHPUnitIntegration;

    /**
     * @var TimerFactory | MockInterface
     */
    private $timerFactory;

    /**
     * @var TimeProvider
     */
    private $timeProvider;

    /**
     * @var TimerService | MockInterface
     */
    private $service;

    /**
     * @var PeriodicTimer | MockInterface
     */
    private $timer;

    protected function setUp()
    {
        $this->timerFactory = \Mockery::mock(TimerFactory::class);
        $this->timeProvider = new TimeProvider();
        $this->timeProvider->freezeTime();
        $this->service = new TimerService($this->timerFactory, $this->timeProvider);
        $this->timer = \Mockery::mock(PeriodicTimer::class);
        $this->service->start();
    }

    public function testAddPeriodicTask()
    {
        $interval = 5;
        $callback = $this->createCallback();
        $this->timerFactory->expects("createPeriodic")->with($interval, $callback)->andReturn($this->timer);
        $this->service->addPeriodicTask("test", $interval, $callback);
        $this->assertSame($this->timer, $this->service->getTimer("test"));
    }

    public function testGetTimerWhenNotExistsThenException()
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->service->getTimer("test");
    }

    public function testRemoveTimerThenRemoved()
    {
        $interval = 5;
        $callback = $this->createCallback();
        $this->timerFactory->expects("createPeriodic")->with($interval, $callback)->andReturn($this->timer);
        $this->service->addPeriodicTask("test", $interval, $callback);
        $this->service->removeTimer("test");
        $this->assertFalse($this->service->hasTimer("test"));
    }

    public function testTickThenTimerUpdate()
    {
        $interval = 5;
        $callback = $this->createCallback();
        $this->timerFactory->expects("createPeriodic")->with($interval, $callback)->andReturn($this->timer);
        $this->service->addPeriodicTask("test", $interval, $callback);
        $this->timer->expects("update")->with($this->timeProvider->now());
        $this->service->tick();
    }

    private function createCallback()
    {
        return function () {
        };
    }
}
