<?php

namespace SAREhub\Commons\Misc;

use PHPUnit\Framework\TestCase;

class DsnBuilderTest extends TestCase {
	
	public function testInproc() {
		$this->assertEquals('inproc://test', DsnBuilder::inproc()->endpoint('test'));
	}
	
	public function testIpc() {
		$this->assertEquals('ipc://test', DsnBuilder::ipc()->endpoint('test'));
	}
	
	public function testTcp() {
		$this->assertEquals('tcp://127.0.0.1', DsnBuilder::tcp()->endpoint('127.0.0.1'));
	}
}
