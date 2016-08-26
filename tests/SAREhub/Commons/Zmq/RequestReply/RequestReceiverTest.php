<?php

namespace SAREhub\Commons\Zmq\RequestReply;

require_once dirname(__DIR__).'/ZmqTestCase.php';
use SAREhub\Commons\Zmq\ZmqTestCase;

class RequestReceiverTest extends ZmqTestCase {
	
	public function testReceiveRequest() {
		$socketMock = $this->createSocketMock();
		$socketMock->expects($this->once())->method('recv')->willReturn('request');
		
		$receiver = new RequestReceiver($socketMock);
		$this->assertEquals('request', $receiver->receiveRequest());
		
	}
	
	public function testSendReply() {
		$socketMock = $this->createSocketMock();
		$socketMock->expects($this->once())->method('send')->with('reply');
		
		$receiver = new RequestReceiver($socketMock);
		$receiver->sendReply('reply');
	}
}
