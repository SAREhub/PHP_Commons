<?php


namespace SAREhub\Commons\Time;


class PeriodicTaskDefinition
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var int
     */
    private $interval;

    /**
     * @var callable
     */
    private $callback;

    public function __construct(string $id, int $interval, callable $callback)
    {
        $this->id = $id;
        $this->interval = $interval;
        $this->callback = $callback;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getInterval(): int
    {
        return $this->interval;
    }

    public function getCallback(): callable
    {
        return $this->callback;
    }
}
