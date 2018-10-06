<?php


namespace SAREhub\Commons\Time;

/**
 * Provide "real" epoch timestamp(from php functions)
 */
class RealTimeProvider implements TimeProvider
{

    public function get(): int
    {
        return time();
    }

    public function getInMilliseconds(): int
    {
        return (int)($this->getInMicroseconds() * 1000);
    }

    public function getInMicroseconds(): float
    {
        return microtime(true);
    }
}