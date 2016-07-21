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
	
	public function testHas() {
		$parameters = new Parameters(['param1' => 1, 'param2' => 2]);
		$this->assertTrue($parameters->has('param1'));
	}
	
	public function testGetRequired() {
		$parameters = new Parameters(['param1' => 1, 'param2' => 2]);
		$this->assertEquals(2, $parameters->getRequired('param2'));
	}
	
	/**
	 * @expectedException SAREhub\Commons\Misc\NotFoundParameterException
	 */
	public function testNotFoundRequired() {
		$parameters = new Parameters(['param1' => 1, 'param2' => 2]);
		$parameters->getRequired('param3');
	}
	
	public function testGetAsMap() {
		$parameters = new Parameters(['param1' => 1, 'param2' => ['param3' => 3]]);
		$parameters2 = $parameters->getAsMap('param2');
		$this->assertInstanceOf(Parameters::class, $parameters2);
		$this->assertEquals(['param3' => 3], $parameters2->getAll());
	}
	
	public function testGetAsMapDefault() {
		$parameters = new Parameters(['param1' => 1]);
		$parameters2 = $parameters->getAsMap('param2', ['param3' => '3']);
		$this->assertInstanceOf(Parameters::class, $parameters2);
		$this->assertEquals(['param3' => 3], $parameters2->getAll());
	}
	
	public function testGetRequiredAsMap() {
		$parameters = new Parameters(['param1' => 1, 'param2' => ['param3' => 3]]);
		$parameters2 = $parameters->getRequiredAsMap('param2');
		$this->assertInstanceOf(Parameters::class, $parameters2);
		$this->assertEquals(['param3' => 3], $parameters2->getAll());
	}
	
	/**
	 * @expectedException SAREhub\Commons\Misc\NotFoundParameterException
	 */
	public function testNotFoundRequiredAsMap() {
		$parameters = new Parameters(['param1' => 1, 'param2' => ['param3' => 3]]);
		$parameters->getRequiredAsMap('param3');
	}
	
	public function testJsonSerialize() {
		$raw = ['param1' => 1, 'param2' => 2];
		$parameters = new Parameters($raw);
		$this->assertJsonStringEqualsJsonString(json_encode(($raw)), json_encode($parameters));
	}
	
}
