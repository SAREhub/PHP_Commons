<?php

namespace SAREhub\Commons\Misc;

/**
 * Helper class for generate UUID
 */
class UuidGenerator
{

    /**
     * @param string $prefix
     * @return string
     */
    public static function generate($prefix = '')
    {
        return uniqid($prefix, true);
    }
}