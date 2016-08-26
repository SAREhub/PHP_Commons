<?php

namespace SAREhub\Commons\Zmq;

use PHPUnit\Framework\TestCase;

class ZmqTestCase extends TestCase {
	
	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject
	 */
	protected function createContextMock() {
		return $this->getMockBuilder(\ZMQContext::class)->disableOriginalConstructor()->getMock();
	}
	
	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject
	 */
	protected function createSocketMock() {
		return $this->getMockBuilder(\ZMQSocket::class)->disableOriginalConstructor()->getMock();
	}
}