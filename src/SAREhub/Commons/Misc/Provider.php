<?php


namespace SAREhub\Commons\Misc;

/**
 * Interface used in DI containers like php-di to standardize factory pattern.
 * Inspired by JSR-330 http://javax-inject.github.io/javax-inject/api/index.html.
 */
interface Provider
{
    /**
     * Can return any value to injection like instance of some class and return it or simple value.
     * @return mixed
     */
    public function get();
}