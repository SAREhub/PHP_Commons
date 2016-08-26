<?php

namespace SAREhub\Commons\Zmq\RequestReply;

require_once dirname(__DIR__).'/ZmqTestCase.php';
use SAREhub\Commons\Zmq\ZmqTestCase;

class RequestReceiverBuilderTest extends ZmqTestCase {
	
	public function testBuild() {
		$dsn = 'tcp://127.0.0.1';
		$socketMock = $this->createSocketMock();
		$contextMock = $this->createContextMock();
		$contextMock->expects($this->once())->method('getSocket')
		  ->with(\Zmq::SOCKET_REP, null, null)
		  ->willReturn($socketMock);
		$socketMock->expects($this->once())->method('bind')->with($dsn)->willReturn($socketMock);
		
		$this->assertInstanceOf(RequestReceiver::class, RequestReceiver::builder()
		  ->context($contextMock)
		  ->bind($dsn)->build()
		);
	}
}
