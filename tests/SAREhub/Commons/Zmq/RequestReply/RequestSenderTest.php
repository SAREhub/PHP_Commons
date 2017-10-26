<?php

require_once dirname(__DIR__).'/ZmqTestCase.php';

use SAREhub\Commons\Zmq\RequestReply\RequestSender;
use SAREhub\Commons\Zmq\ZmqTestCase;

class RequestSenderTest extends ZmqTestCase {
	
	/**
	 * @var RequestSender
	 */
	private $sender;
	
	public function setUp() {
		parent::setUp();
		$this->sender = new RequestSender($this->contextMock);
	}
	
	public function testConnectWhenFirstTimeUsed() {
		$this->contextMock->expects($this->once())->method('getSocket')
		  ->with(\Zmq::SOCKET_REQ, null, null)
		  ->willReturn($this->socketMock);
		
		$this->socketMock->expects($this->once())
		  ->method('connect')
		  ->with($this->dsnMock);
		
		$this->assertSame($this->sender, $this->sender->connect($this->dsnMock));
		$this->assertSame($this->dsnMock, $this->sender->getDsn());
		$this->assertSame($this->socketMock, $this->sender->getSocket());
	}
	
	public function testIsConnected() {
		$this->sender->connect($this->dsnMock);
		$this->assertTrue($this->sender->isConnected());
	}
	
	public function testIsConnectedWhenNotConnected() {
		$this->assertFalse($this->sender->isConnected());
	}
	
	/**
	 * @expectedException \LogicException
	 */
	public function testConnectWhenConnected() {
		$this->sender->connect($this->dsnMock);
		$this->sender->connect($this->dsnMock);
	}
	
	public function testDisconnect() {
		$this->sender->connect($this->dsnMock);
		$this->sender->disconnect();
		$this->assertNull($this->sender->getDsn());
	}
	
	public function testDisconnectWhenNotConnected() {
		$this->socketMock->expects($this->never())->method('disconnect');
		$this->sender->disconnect();
	}
	
	public function testSendRequest() {
		$this->socketMock->expects($this->once())
		  ->method('send')
		  ->with('request');
		$this->sender->sendRequest('request', 0);
	}
	
	public function testSendRequestDontWait() {
		$this->socketMock->expects($this->once())
		  ->method('send')
		  ->with('request', \ZMQ::MODE_DONTWAIT);
		$this->sender->sendRequest('request', RequestSender::DONT_WAIT);
	}
	
	public function testReceiveReply() {
		$this->socketMock->expects($this->once())
		  ->method('recv')
		  ->willReturn('reply', 0);
		$this->assertEquals('reply', $this->sender->receiveReply());
	}
	
	public function testReceiveReplyDontWait() {
		$this->socketMock->expects($this->once())
		  ->method('recv')
		  ->willReturn('reply', \ZMQ::MODE_DONTWAIT);
		$this->assertEquals('reply', $this->sender->receiveReply(RequestSender::DONT_WAIT));
	}
}
