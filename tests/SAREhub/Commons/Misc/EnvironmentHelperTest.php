<?php

namespace SAREhub\Commons\Misc;

use PHPUnit\Framework\TestCase;

class EnvironmentHelperTest extends TestCase
{

    const VARIABLE = 'ENV_HELPER_TEST';

    public function setUp()
    {
        putenv(self::VARIABLE);
    }

    public function testGetVarWhenExists()
    {
        putenv(self::VARIABLE . '=1');
        $this->assertEquals(1, EnvironmentHelper::getVar(self::VARIABLE));
    }

    public function testGetVarWhenNotExists()
    {
        $this->assertEquals(1, EnvironmentHelper::getVar(self::VARIABLE, 1));
    }

    public function testGetVarsWhenExists()
    {
        putenv(self::VARIABLE . '=1');
        $this->assertEquals([self::VARIABLE => 1], EnvironmentHelper::getVars([self::VARIABLE => 2]));
    }

    public function testGetVarsWhenNotExists()
    {
        $this->assertEquals([self::VARIABLE => 2], EnvironmentHelper::getVars([self::VARIABLE => 2]));
    }
}
