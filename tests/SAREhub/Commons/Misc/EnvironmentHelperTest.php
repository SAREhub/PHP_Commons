<?php

namespace SAREhub\Commons\Misc;

use PHPUnit\Framework\TestCase;

class EnvironmentHelperTest extends TestCase
{

    public function testGetVarWhenExists()
    {
        $variableName = $this->getName();
        $this->putenv($variableName, 1);
        $this->assertEquals(1, EnvironmentHelper::getVar($variableName));
    }

    public function testGetVarWhenNotExists()
    {
        $defaultValue = 1;
        $this->assertEquals($defaultValue, EnvironmentHelper::getVar("not_exists_var", $defaultValue));
    }

    public function testGetRequiredVarWhenExists()
    {
        $variableName = $this->getName();
        $this->putenv($variableName, 1);
        $this->assertEquals(1, EnvironmentHelper::getRequiredVar($variableName));
    }

    /**
     * @throws EnvVarNotFoundException
     */
    public function testGetRequiredVarWhenNotExists()
    {
        $this->expectException(EnvVarNotFoundException::class);
        EnvironmentHelper::getRequiredVar("not_exists_var");
    }

    public function testGetVarsWhenExists()
    {
        $variableName = $this->getName();
        $value = 1;
        $this->putenv($variableName, $value);
        $this->assertEquals([$variableName => $value], EnvironmentHelper::getVars([$variableName => null]));
    }

    public function testGetVarsWhenNotExists()
    {
        $variableName = "not_exists_var";
        $defaultValue = 1;
        $this->assertEquals([$variableName => $defaultValue], EnvironmentHelper::getVars([$variableName => $defaultValue]));
    }

    public function testGetVarsWithCustomPrefix()
    {
        $prefix = "prefix_";
        $variableName = $this->getName();
        $this->putenv($prefix . $variableName, 1);
        $this->assertEquals([$variableName => 1], EnvironmentHelper::getVars([$variableName => null], $prefix));
    }

    private function putenv(string $name, $value)
    {
        putenv("$name=$value");
    }
}
