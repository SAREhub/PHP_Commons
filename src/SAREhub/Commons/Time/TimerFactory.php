<?php

namespace SAREhub\Commons\Time;

use SAREhub\Commons\Misc\PeriodicTimer;

class TimerFactory
{
    public function createPeriodic(int $interval, callable $callback): PeriodicTimer
    {
        return PeriodicTimer::every($interval)->call($callback);
    }
}