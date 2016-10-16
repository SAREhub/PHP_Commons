<?php

use SAREhub\Commons\Misc\Dsn;
use SAREhub\Commons\Zmq\ZmqSocketBase;

class TestZmqSocketBase extends ZmqSocketBase  {

}

class ZmqSocketBaseTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var PHPUnit_Framework_MockObject_MockObject
	 */
	private $socket;

	/**
	 * @var ZmqSocketBase
	 */
	private $base;

	private $dsn;
	private $dsn2;

	public function testIsBindedWhenNotBindedThenReturnFalse() {
		$this->assertFalse($this->base->isBinded());
	}

	public function testBindWhenNotBindedThenSocketCallBind() {
		$this->socket->expects($this->once())->method('bind')->with((string)$this->dsn);
		$this->base->bind($this->dsn);
	}

	public function testBindWhenBindedThenThrowException() {
		$this->base->bind($this->dsn);
		$this->expectException(LogicException::class);
		$this->base->bind($this->dsn);
	}

	public function testBindThenReturnThis() {
		$this->assertSame($this->base, $this->base->bind($this->dsn));
	}

	public function testIsBindWhenBindedThenReturnTrue() {
		$this->base->bind($this->dsn);
		$this->assertTrue($this->base->isBinded());
	}

	public function testUnbindWhenNotBindedThenNoop() {
		$this->socket->expects($this->never())->method('unbind');
		$this->base->unbind();
	}

	public function testUnbindWhenBindedThenSocketCallUnbind() {
		$this->base->bind($this->dsn);
		$this->socket->expects($this->once())->method('unbind')->with((string)$this->dsn);
		$this->base->unbind();
	}

	public function testUnbindWhenUnbindedThenIsBindedReturnFalse() {
		$this->base->bind($this->dsn);
		$this->base->unbind();
		$this->assertFalse($this->base->isBinded());
	}

	public function testConnectToThenSocketConnect() {
		$this->socket->expects($this->once())->method('connect')->with((string)$this->dsn);
		$this->base->connectTo($this->dsn);
	}

	public function testConnectToWhenConnectedThenNoop() {
		$this->base->connectTo($this->dsn);
		$this->socket->expects($this->never())->method('connect');
		$this->base->connectTo($this->dsn);
	}

	public function testIsConnectedToAnyWhenNotConnectedThenReturnFalse() {
		$this->assertFalse($this->base->isConnectedToAny());
	}

	public function testIsConnectedToAnyWhenConnectedThenReturnTrue() {
		$this->base->connectTo($this->dsn);
		$this->assertTrue($this->base->isConnectedToAny());
	}

	public function testIsConnectedToWhenConnectedThenReturnFalse() {
		$this->assertFalse($this->base->isConnectedTo($this->dsn));
	}

	public function testIsConnectedToWhenConnectedThenReturnTrue() {
		$this->base->connectTo($this->dsn);
		$this->assertTrue($this->base->isConnectedTo($this->dsn));
	}

	public function testDisconnectFromWhenConnectedThenSocketDisconnect() {
		$this->socket->expects($this->once())->method('disconnect')->with((string)$this->dsn);
		$this->base->connectTo($this->dsn);
		$this->base->disconnectFrom($this->dsn);
	}

	public function testDisconnectFromWhenNotConnectedThenNoop() {
		$this->socket->expects($this->never())->method('disconnect');
		$this->base->disconnectFrom($this->dsn);
	}

	public function testIsConnectedToWhenDisconnectThenReturnFalse() {
		$this->base->connectTo($this->dsn);
		$this->base->disconnectFrom($this->dsn);
		$this->assertFalse($this->base->isConnectedTo($this->dsn));
	}

	public function testDisconnectFromAllWhenConnectedThenSocketDisconnect() {
		$this->base->connectTo($this->dsn);
		$this->base->connectTo($this->dsn2);

		$this->socket->expects($this->atLeast(2))->method('disconnect')->withConsecutive([(string)$this->dsn], [(string)$this->dsn2]);
		$this->base->disconnectFromAll();
	}

	public function testDisconnectFromAllWhenNotConnectedThenNoop() {
		$this->socket->expects($this->never())->method('disconnect');
		$this->base->disconnectFromAll();
	}

	public function testIsConnectedToAnyWhenDisconnectFromAllThenReturnFalse() {
		$this->base->connectTo($this->dsn);
		$this->base->connectTo($this->dsn2);
		$this->base->disconnectFromAll();
		$this->assertFalse($this->base->isConnectedToAny());
	}

	public function testIsBindedOrConnectedWhenNotBindedOrConnectedThenReturnFalse() {
		$this->assertFalse($this->base->isBindedOrConnected());
	}

	public function testIsBindedOrConnectedWhenConnectedThenReturnTrue() {
		$this->base->connectTo($this->dsn);
		$this->assertTrue($this->base->isBindedOrConnected());
	}

	public function testIsBindedOrConnectedWhenBindedThenReturnTrue() {
		$this->base->bind($this->dsn);
		$this->assertTrue($this->base->isBindedOrConnected());
	}

	protected function setUp() {
		parent::setUp();
		$this->socket = $this->createMock(ZMQSocket::class);
		$this->base = new TestZmqSocketBase($this->socket);

		$this->dsn = Dsn::tcp()->endpoint('127.1.0.1:5000');
		$this->dsn2 = Dsn::tcp()->endpoint('127.1.0.1:5001');
	}
}
