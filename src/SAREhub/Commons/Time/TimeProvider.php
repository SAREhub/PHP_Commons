<?php


namespace SAREhub\Commons\Time;

/**
 * Interface for provide epoch timestamp with different precession
 */
interface TimeProvider
{
    public function get(): int;

    public function getInMilliseconds(): int;

    public function getInMicroseconds(): float;
}