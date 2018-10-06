<?php


namespace SAREhub\Commons\Time;

/**
 * Provide epoch timestamp sets by user - good for testing time dependent components.
 */
class StaticTimeProvider implements TimeProvider
{
    /**
     * @var int
     */
    private $time = 0;

    /**
     * @var int
     */
    private $milliTime = 0;

    /**
     * @var float
     */
    private $microTime = 0.0;

    public function get(): int
    {
        return $this->time;
    }

    public function getInMilliseconds(): int
    {
        return $this->milliTime;
    }

    public function getInMicroseconds(): float
    {
        return $this->microTime;
    }

    public function setTime(int $time): void
    {
        $this->time = $time;
    }

    public function setMilliTime(int $milliTime): void
    {
        $this->milliTime = $milliTime;
    }

    public function setMicroTime(float $microTime): void
    {
        $this->microTime = $microTime;
    }


}