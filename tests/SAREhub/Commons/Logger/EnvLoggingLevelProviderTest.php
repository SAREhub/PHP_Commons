<?php

namespace SAREhub\Commons\Logger;

use PHPUnit\Framework\TestCase;

class EnvLoggingLevelProviderTest extends TestCase
{

    /**
     * @var string
     */
    private $variableName;

    /**
     * @var EnvLoggingLevelProvider
     */
    private $provider;

    protected function setUp()
    {
        $this->variableName = "test_loggging_level_envar";
        $this->provider = new EnvLoggingLevelProvider($this->variableName);
        putenv($this->variableName);
    }

    public function testGetLevelWhenEnvVarSets()
    {
        $expected = "test_logging_level";

        putenv($this->variableName . "=$expected");
        $this->assertEquals($expected, $this->provider->get());
    }

    public function testGetLevelWhenEnvVarNotSets()
    {
        $this->assertEquals(EnvLoggingLevelProvider::DEFAULT_LOGGING_LEVEL, $this->provider->get());
    }
}
