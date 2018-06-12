<?php


namespace SAREhub\Commons\Misc;


class EnvVarNotFoundException extends \RuntimeException
{
    const MESSAGE_FORMAT = "Not found value of env var: %s";

    public static function create(string $varName): self
    {
        return new self(sprintf(self::MESSAGE_FORMAT, $varName));
    }
}