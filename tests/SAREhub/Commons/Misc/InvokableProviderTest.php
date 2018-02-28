<?php

namespace SAREhub\Commons\Misc;

use PHPUnit\Framework\TestCase;

class InvokableProviderTest extends TestCase
{

    public function test__invoke()
    {
        $provider = new class() extends InvokableProvider
        {
            public function get()
            {
                return "provider_value";
            }
        };

        $this->assertEquals("provider_value", $provider());
    }
}
