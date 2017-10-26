<?php

require_once dirname(__DIR__) . '/ZmqTestCase.php';

use SAREhub\Commons\Zmq\RequestReply\RequestReceiver;
use SAREhub\Commons\Zmq\ZmqTestCase;

class RequestReceiverTest extends ZmqTestCase
{

    /**
     * @var RequestReceiver
     */
    private $receiver;

    public function setUp()
    {
        parent::setUp();
        $this->receiver = new RequestReceiver($this->contextMock);
    }

    public function testBindWhenFirstTime()
    {
        $this->contextMock->expects($this->once())->method('getSocket')
            ->with(\Zmq::SOCKET_REP, null, null)
            ->willReturn($this->socketMock);
        $this->socketMock->expects($this->once())->method('bind')->with((string)$this->dsnMock);

        $this->receiver->bind($this->dsnMock);
        $this->assertSame($this->dsnMock, $this->receiver->getDsn());
        $this->assertSame($this->socketMock, $this->receiver->getSocket());
    }

    /**
     * @expectedException \LogicException
     */
    public function testBindWhenBinded()
    {
        $this->receiver->bind($this->dsnMock);
        $this->receiver->bind($this->dsnMock);
    }

    public function testIsBinded()
    {
        $this->receiver->bind($this->dsnMock);
        $this->assertTrue($this->receiver->isBinded());
    }

    public function testUnbind()
    {
        $this->receiver->bind($this->dsnMock);
        $this->socketMock->expects($this->once())->method('unbind')->with((string)$this->dsnMock);
        $this->receiver->unbind();
        $this->assertNull($this->receiver->getDsn());
    }

    public function testReceiveRequest()
    {
        $this->socketMock->expects($this->once())
            ->method('recv')
            ->willReturn('request', 0);

        $this->assertEquals('request', $this->receiver->receiveRequest());
    }

    public function testReceiveRequestNotWait()
    {
        $this->socketMock->expects($this->once())
            ->method('recv')
            ->willReturn('request', \ZMQ::MODE_DONTWAIT);

        $this->assertEquals('request', $this->receiver->receiveRequest(false));
    }

    public function testSendReply()
    {
        $this->socketMock->expects($this->once())
            ->method('send')
            ->with('reply', 0);

        $this->receiver->sendReply('reply');
    }

    public function testSendReplyNotWait()
    {
        $this->socketMock->expects($this->once())
            ->method('send')
            ->with('reply', \ZMQ::MODE_DONTWAIT);

        $this->receiver->sendReply('reply', false);
    }
}
