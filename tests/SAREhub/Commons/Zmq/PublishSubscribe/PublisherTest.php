<?php

use PHPUnit\Framework\TestCase;
use SAREhub\Commons\Misc\Dsn;
use SAREhub\Commons\Zmq\PublishSubscribe\Publisher;

class PublisherTest extends TestCase {
	
	/**
	 * @var PHPUnit_Framework_MockObject_MockObject
	 */
	private $socketMock;
	
	/**
	 * @var Publisher
	 */
	private $publisher;
	
	private $dsn;
    private $dsn2;
	
	public function testCreateThenSocketTypeReturnPub() {
		$contextMock = $this->createMock(ZMQContext::class);
		$contextMock->expects($this->once())->method('getSocket')
		  ->with(ZMQ::SOCKET_PUB)->willReturn($this->socketMock);
		
		$publisher = Publisher::inContext($contextMock);
		$this->assertSame($this->socketMock, $publisher->getSocket());
	}

	public function testPublishWhenNotBindedThenThrowException() {
		$this->expectException(LogicException::class);
		$this->publisher->publish("topic", "message");
	}
	
	public function testPublishWhenBindedThenSocketSend() {
		$this->publisher->bind($this->dsn);
		$this->socketMock->expects($this->once())->method('sendmulti')
		  ->with(['topic', 'message'], ZMQ::MODE_DONTWAIT);
		$this->publisher->publish("topic", "message");
	}

	public function testPublishWhenConnectedThenSocketSend() {
	    $this->publisher->connectTo($this->dsn);
        $this->socketMock->expects($this->once())->method('sendmulti')
            ->with(['topic', 'message'], ZMQ::MODE_DONTWAIT);
        $this->publisher->publish("topic", "message");
    }
	
	public function testPublishWhenWaitModeThenSocketSendWait() {
		$this->publisher->bind($this->dsn);
		$this->socketMock->expects($this->once())->method('sendmulti')
		  ->with(['topic', 'message'], 0);
		$this->publisher->publish("topic", "message", true);
	}

	protected function setUp() {
		parent::setUp();
		$contextMock = $this->createMock(ZMQContext::class);
		$this->socketMock = $this->createMock(ZMQSocket::class);
		$contextMock->method('getSocket')->willReturn($this->socketMock);
		$this->publisher = Publisher::inContext($contextMock);
		
		$this->dsn = Dsn::tcp()->endpoint('127.1.0.1:5000');
        $this->dsn2 = Dsn::tcp()->endpoint('127.1.0.1:5001');
	}
}
