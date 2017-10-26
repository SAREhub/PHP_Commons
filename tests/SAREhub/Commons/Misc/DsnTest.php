<?php

namespace SAREhub\Commons\Misc;

use PHPUnit\Framework\TestCase;

class DsnTest extends TestCase
{

    public function testInproc()
    {
        $this->assertEquals('inproc://test', Dsn::inproc()->endpoint('test'));
    }

    public function testIpc()
    {
        $this->assertEquals('ipc://test', Dsn::ipc()->endpoint('test'));
    }

    public function testTcp()
    {
        $this->assertEquals('tcp://127.0.0.1', Dsn::tcp()->endpoint('127.0.0.1'));
    }
}
