<?php


namespace SAREhub\Commons\Misc;

use PHPUnit\Framework\TestCase;

class ParametersTest extends TestCase {
	
	public function testGet() {
		$parameters = new Parameters(['param1' => 1, 'param2' => 2]);
		$this->assertEquals(1, $parameters->get('param1'));
	}
	
	public function testGetDefault() {
		$parameters = new Parameters(['param1' => 1, 'param2' => 2]);
		$this->assertEquals(3, $parameters->get('param3', 3));
		
	}
	
}
