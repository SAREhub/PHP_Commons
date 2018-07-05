<?php

namespace SAREhub\Commons\Time;

use SAREhub\Commons\Misc\PeriodicTimer;
use SAREhub\Commons\Misc\TimeProvider;
use SAREhub\Commons\Service\ServiceSupport;

class TimerService extends ServiceSupport
{
    /**
     * @var TimerFactory
     */
    private $factory;

    /**
     * @var PeriodicTimer[]
     */
    private $timers = [];

    /**
     * @var TimeProvider
     */
    private $timeProvider;

    public function __construct(TimerFactory $factory, TimeProvider $timeProvider)
    {
        $this->factory = $factory;
        $this->timeProvider = $timeProvider;
    }

    public function addPeriodicTask(string $id, int $interval, callable $callback): self
    {
        $this->timers[$id] = $this->factory->createPeriodic($interval, $callback);
        return $this;
    }

    public function getTimer(string $id): PeriodicTimer
    {
        if ($this->hasTimer($id)) {
            return $this->timers[$id];
        }
        throw new \OutOfBoundsException("Timer with id '$id' not found");
    }

    public function hasTimer(string $id): bool
    {
        return !empty($this->timers[$id]);
    }

    public function removeTimer(string $id): self
    {
        unset($this->timers[$id]);
        return $this;
    }

    /**
     * @return PeriodicTimer[]
     */
    public function getTimers(): array
    {
        return $this->timers;
    }

    protected function doTick()
    {
        foreach ($this->getTimers() as $timer) {
            $timer->update($this->timeProvider->now());
        }
    }
}
