<?php

namespace SAREhub\Commons\Secret;

use PHPUnit\Framework\TestCase;

class SimpleSecretValueProviderTest extends TestCase
{

    public function testGet()
    {
        $provider = new SimpleSecretValueProvider();
        $this->assertEquals("test", $provider->get("test"));
    }
}
