<?php

namespace SAREhub\Commons\Zmq\RequestReply;

require_once dirname(__DIR__).'/ZmqTestCase.php';
use SAREhub\Commons\Zmq\ZmqTestCase;

class RequestSenderTest extends ZmqTestCase {
	
	public function testSendRequest() {
		$socketMock = $this->createSocketMock();
		$socketMock->expects($this->once())->method('send')->with('request');
		$socketMock->expects($this->once())->method('recv')->willReturn('reply');
		
		$sender = new RequestSender($socketMock);
		$this->assertEquals('reply', $sender->sendRequest('request'));
		
	}
}
