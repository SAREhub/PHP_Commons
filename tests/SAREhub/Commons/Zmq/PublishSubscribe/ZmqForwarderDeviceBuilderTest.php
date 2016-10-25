<?php

use PHPUnit\Framework\TestCase;
use SAREhub\Commons\Zmq\PublishSubscribe\Publisher;
use SAREhub\Commons\Zmq\PublishSubscribe\Subscriber;
use SAREhub\Commons\Zmq\PublishSubscribe\ZmqForwarderDevice;
use SAREhub\Commons\Zmq\PublishSubscribe\ZmqForwarderDeviceBuilder;

class ZmqForwarderDeviceBuilderTest extends TestCase {
	
	private $frontend;
	private $backend;
	
	protected function setUp() {
		$this->frontend = $this->createMock(Subscriber::class);
		$this->frontend->method('getSocket')->willReturn($this->createMock(ZMQSocket::class));
		
		$this->backend = $this->createMock(Publisher::class);
		$this->backend->method('getSocket')->willReturn($this->createMock(ZMQSocket::class));
	}
	
	
	public function testBuildWhenFrontendAndBackendSetsThenZmqDeviceInstance() {
		$this->assertInstanceOf(ZmqForwarderDevice::class, ZmqForwarderDevice::getBuilder()
		  ->frontend($this->frontend)
		  ->backend($this->backend)
		  ->build());
	}
	
	public function testBuildWhenFrontendTopicNotSetsThenSetEmptyTopic() {
		$this->frontend->expects($this->once())->method('subscribe')->with('');
		$this->assertInstanceOf(ZmqForwarderDevice::class, ZmqForwarderDevice::getBuilder()
		  ->frontend($this->frontend)
		  ->backend($this->backend)
		  ->build());
	}
	
	public function testBuildWhenFrontendHasAnyTopicThenNotAddEmptyTopic() {
		$this->frontend->method('getTopics')->willReturn(['topic']);
		$this->frontend->expects($this->never())->method('subscribe');
		$this->assertInstanceOf(ZmqForwarderDevice::class, ZmqForwarderDevice::getBuilder()
		  ->frontend($this->frontend)
		  ->backend($this->backend)
		  ->build());
	}
}
