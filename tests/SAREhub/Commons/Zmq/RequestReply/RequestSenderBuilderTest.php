<?php

namespace SAREhub\Commons\Zmq\RequestReply;

require_once dirname(__DIR__).'/ZmqTestCase.php';
use SAREhub\Commons\Zmq\ZmqTestCase;

class RequestSenderBuilderTest extends ZmqTestCase {
	
	public function testBuild() {
		$dsn = 'tcp://127.0.0.1';
		$socketMock = $this->createSocketMock();
		$contextMock = $this->createContextMock();
		$contextMock->expects($this->once())->method('getSocket')
		  ->with(\Zmq::SOCKET_REQ, null, null)
		  ->willReturn($socketMock);
		$socketMock->expects($this->once())->method('connect')->with($dsn)->willReturn($socketMock);
		
		$this->assertInstanceOf(RequestSender::class, RequestSender::builder()
		  ->context($contextMock)
		  ->connect($dsn)->build()
		);
	}
}
