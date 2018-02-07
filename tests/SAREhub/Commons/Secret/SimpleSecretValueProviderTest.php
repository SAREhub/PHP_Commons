<?php

namespace SAREhub\Commons\Secret;

use PHPUnit\Framework\TestCase;

class SimpleSecretValueProviderTest extends TestCase
{

    public function testGetValue()
    {
        $provider = new SimpleSecretValueProvider();
        $this->assertEquals("test", $provider->getValue("test"));
    }
}
