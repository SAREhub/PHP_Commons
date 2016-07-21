<?php


namespace SAREhub\Commons\Misc;

use PHPUnit\Framework\TestCase;

class GenericFactoryTest extends TestCase {
	
	
	public function testRegisterCreator() {
		$creator = function () {
			
		};
		$factory = new GenericFactory();
		$factory->registerCreator('test', $creator);
		
		$this->assertEquals($creator, $factory->getCreator('test'));
	}
	
	public function testRegisterCreators() {
		$creators = [
		  'test1' => function () {
		  },
		  'test2' => function () {
		  }
		];
		$factory = new GenericFactory();
		$factory->registerCreators($creators);
		
		$this->assertEquals($creators['test1'], $factory->getCreator('test1'));
		$this->assertEquals($creators['test2'], $factory->getCreator('test2'));
	}
	
	public function testCreate() {
		$creator = function ($data) {
			return $data;
		};
		
		$factory = new GenericFactory();
		$factory->registerCreator('test1', $creator);
		
		$this->assertEquals(['param1' => 1], $factory->create('test1', ['param1' => 1]));
	}
}
