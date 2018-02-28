<?php


namespace SAREhub\Commons\Misc;


/**
 * Used in DI containers like php-di to standardize factory pattern.
 * Inspired by JSR-330 http://javax-inject.github.io/javax-inject/api/index.html.
 * Simple call provider get() method
 */
abstract class InvokableProvider implements Provider
{

    public function __invoke()
    {
        return $this->get();
    }
}