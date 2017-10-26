<?php

namespace SAREhub\Commons\Zmq;

use PHPUnit\Framework\TestCase;
use SAREhub\Commons\Misc\Dsn;

class ZmqTestCase extends TestCase
{

    protected $dsnMock;
    protected $contextMock;
    protected $socketMock;

    public function setUp()
    {
        $this->dsnMock = $this->createDsnMock();
        $this->contextMock = $this->createContextMock();
        $this->socketMock = $this->createSocketMock();
        $this->contextMock->method('getSocket')->willReturn($this->socketMock);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createContextMock()
    {
        return $this->createMock(\ZMQContext::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createSocketMock()
    {
        return $this->createMock(\ZMQSocket::class);
    }

    protected function createDsnMock()
    {
        return Dsn::tcp()->endpoint('127.0.0.1');
    }
}