<?php

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use SAREhub\Commons\Service\ServiceSupport;

class TestServiceSupport extends ServiceSupport {
}

class ServiceSupportTest extends TestCase {
	
	/**
	 * @var ServiceSupport
	 */
	private $service;
	
	protected function setUp() {
		parent::setUp();
		$spyMethods = ['doStart', 'doTick', 'doStop'];
		$this->service = $this->createPartialMock(TestServiceSupport::class, $spyMethods);
	}
	
	public function testIsStartedWhenCreatedThenReturnFalse() {
		$this->assertFalse($this->service->isStarted());
	}
	
	public function testIsRunningWhenCreatedThenReturnFalse() {
		$this->assertFalse($this->service->isRunning());
	}
	
	public function testStartThenIsStartedReturnTrue() {
		$this->service->start();
		$this->assertTrue($this->service->isStarted());
	}
	
	public function testStartWhenStoppedThenCallDoStart() {
		$spy = $this->getMethodSpy('doStart');
		$this->service->start();
		$this->assertEquals(1, $spy->getInvocationCount());
	}
	
	public function testStartWhenStartedThenNotCallDoStart() {
		$this->service->start();
		$spy = $this->getMethodSpy('doStart');
		$this->service->start();
		$this->assertFalse($spy->hasBeenInvoked());
	}
	
	public function testIsStoppedWhenCreatedThenReturnTrue() {
		$this->assertTrue($this->service->isStopped());
	}
	
	public function testIsStoppedWhenStartedThenReturnFalse() {
		$this->service->start();
		$this->assertFalse($this->service->isStopped());
	}
	
	public function testStopWhenStartedThenIsStoppedReturnTrue() {
		$this->service->start();
		$this->service->stop();
		$this->assertTrue($this->service->isStopped());
	}
	
	public function testStopThenIsStartedReturnFalse() {
		$this->service->stop();
		$this->assertFalse($this->service->isStarted());
	}
	
	public function testStopThenIsRunningReturnFalse() {
		$this->service->stop();
		$this->assertFalse($this->service->isRunning());
	}
	
	public function testStopWhenStartedThenCallDoStop() {
		$this->service->start();
		$spy = $this->getMethodSpy('doStop');
		$this->service->stop();
		$this->assertEquals(1, $spy->getInvocationCount());
	}
	
	public function testStopWhenStoppedThenNotCallDoStop() {
		$spy = $this->getMethodSpy('doStop');
		$this->service->stop();
		$this->assertFalse($spy->hasBeenInvoked());
	}
	
	public function testTickWhenRunningThenDoTick() {
		$this->service->start();
		$spy = $this->getMethodSpy('doTick');
		$this->service->tick();
		$this->assertEquals(1, $spy->getInvocationCount());
	}
	
	public function testTickWhenNotRunningThenNotDoTick() {
		$spy = $this->getMethodSpy('doTick');
		$this->service->tick();
		$this->assertFalse($spy->hasBeenInvoked());
	}
	
	public function testTickWhenDoTickThrowExceptionThenThrowed() {
		$this->service->start();
		$this->service->method('doTick')->willThrowException(new Exception('exception'));
		$this->expectException(\Exception::class);
		$this->service->tick();
	}
	
	public function testTickWhenDoTickThrowExceptionAndDoStopThrowExceptionThenThrowOrginalException() {
		$this->service->start();
		$orginalException = new Exception('exception');
		$this->service->method('doTick')->willThrowException($orginalException);
		$this->service->method('doStop')->willThrowException(new Exception('exception2'));
		try {
			$this->service->tick();
		} catch (\Exception $e) {
			$this->assertSame($orginalException, $e);
		}
	}
	
	public function testTickWhenDoTickThrowExceptionThenStopped() {
		$this->service->start();
		$this->service->method('doTick')->willThrowException(new Exception('exception'));
		try {
			$this->service->tick();
		} catch (\Exception $e) {
			$this->assertFalse($this->service->isRunning());
		}
	}
	
	public function testSetLoggerWhenSetsAndSetSameThenException() {
		$logger = new NullLogger();
		$this->service->setLogger($logger);
		$this->expectException(\LogicException::class);
		$this->service->setLogger($logger);
	}
	
	public function testStartWhenDoStartThrowExceptionThenNotStarted() {
		$this->service->method('doStart')->willThrowException(new Exception('e'));
		$this->expectException(\Exception::class);
		$this->service->start();
		
		$this->assertFalse($this->service->isStarted());
	}
	
	public function testStopWhenDoStopThrowExceptionThenStopped() {
		$this->service->start();
		$this->service->method('doStop')->willThrowException(new Exception('e'));
		$this->expectException(\Exception::class);
		$this->service->stop();
		$this->assertTrue($this->service->isStopped());
	}
	
	private function getMethodSpy($method) {
		$this->service->expects($methodSpy = $this->any())->method($method);
		return $methodSpy;
	}
}
