<?php


namespace SAREhub\Commons\Logger;


use SAREhub\Commons\Misc\EnvironmentHelper;
use SAREhub\Commons\Misc\InvokableProvider;

class EnvLoggingLevelProvider extends InvokableProvider
{
    const DEFAULT_ENV_LOGGING_LEVEL = "LOGGING_LEVEL";
    const DEFAULT_LOGGING_LEVEL = "DEBUG";

    /**
     * @var string
     */
    private $envVar;

    public function __construct(string $envVar = self::DEFAULT_ENV_LOGGING_LEVEL)
    {
        $this->envVar = $envVar;
    }

    public function get(): string
    {
        return EnvironmentHelper::getVar($this->envVar, self::DEFAULT_LOGGING_LEVEL);
    }
}