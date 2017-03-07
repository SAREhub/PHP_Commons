<?php

namespace SAREhub\Commons\Process;

use PHPUnit\Framework\TestCase;


class PcntlSignalsTest extends TestCase {
	
	/** @var PcntlSignals */
	private $signals;
	
	protected function setUp() {
		$this->signals = PcntlSignals::create(function () { });
	}
	
	public function testInstall() {
		$installer = $this->createPartialMock(\stdClass::class, ['__invoke']);
		$callback = [$this->signals, 'dispatchSignal'];
		$installer->expects($this->exactly(5))->method('__invoke')->withConsecutive(
		  [PcntlSignals::SIGHUP, $callback],
		  [PcntlSignals::SIGINT, $callback],
		  [PcntlSignals::SIGTERM, $callback],
		  [PcntlSignals::SIGPIPE, $callback],
		  [PcntlSignals::SIGUSR1, $callback]
		);
		$this->signals = PcntlSignals::create($installer);
	}
	
	public function testHandle() {
		$handlerMock = $this->createSignalHandler();
		$this->signals->handle(PcntlSignals::SIGINT, $handlerMock);
		$this->assertEquals([
		  2 => [
			PcntlSignals::DEFAULT_NAMESPACE => [$handlerMock]
		  ]
		], $this->signals->getHandlers());
	}
	
	public function testHandleSameSignal() {
		$handlerMock = $this->createSignalHandler();
		$this->signals->handle(2, $handlerMock);
		$handlerMock2 = $this->createSignalHandler();
		$this->signals->handle(2, $handlerMock2);
		
		$this->assertEquals([
		  2 => [
			PcntlSignals::DEFAULT_NAMESPACE => [$handlerMock, $handlerMock2]
		  ]
		], $this->signals->getHandlers());
	}
	
	public function testGetHandlersForSignal() {
		$handlerMock = $this->createSignalHandler();
		$this->signals->handle(2, $handlerMock);
		$this->signals->handle(5, $this->createSignalHandler());
		$this->assertEquals([
		  PcntlSignals::DEFAULT_NAMESPACE => [$handlerMock]
		], $this->signals->getHandlersForSignal(2));
	}
	
	public function testDispatchSignal() {
		$handlerMock = $this->createSignalHandler();
		$handlerMock->expects($this->once())->method('__invoke');
		$this->signals->handle(2, $handlerMock);
		$this->signals->dispatchSignal(2);
	}
	
	private function createSignalHandler() {
		return $this->getMockBuilder(\stdClass::class)->setMethods(['__invoke'])->getMock();
	}
}
