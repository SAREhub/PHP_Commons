<?php

namespace SAREhub\Commons\Zmq\PublishSubscribe;

use PHPUnit\Framework\TestCase;

class SubscriptionTopicTest extends TestCase {
	
	public function testMatchWhenMatch() {
		$this->assertTopicMatch('a.b', 'a.b');
	}
	
	public function testMatchWhenNotMatch() {
		$this->assertTopicNotMatch('a.b', 'c.d');
	}
	
	public function testMatchWhenNotEnoughPartsInOtherThenNotMatch() {
		$this->assertTopicNotMatch('a.b', 'a');
	}
	
	public function testMatchWhenNotEnoughPartsInTopicThenNotMatch() {
		$this->assertTopicNotMatch('a', 'a.b');
	}
	
	public function testMatchWhenWildcardOnFirstPartAndMatch() {
		$this->assertTopicMatch('*.b', 'a.b');
	}
	
	public function testMatchWhenWildcardOnFirstPartAndNotMatch() {
		$this->assertTopicNotMatch('*.b', 'a.c');
	}
	
	public function testMatchWhenWildcardOnLastPartAndMatch() {
		$this->assertTopicMatch('a.*', 'a.b');
	}
	
	public function testMatchWhenWildcardOnLastPartAndNotMatch() {
		$this->assertTopicNotMatch('a.*', 'b.c');
	}
	
	public function testMatchWhenWildcardInnerAndMatch() {
		$this->assertTopicMatch('a.*.c', 'a.b.c');
	}
	
	public function testMatchWhenWildcardInnerAndNotMatch() {
		$this->assertTopicNotMatch('a.*.c', 'a.b.d');
	}
	
	private function assertTopicMatch(string $topic, string $other) {
		$this->assertTrue($this->createTopic($topic)->match($other));
	}
	
	private function assertTopicNotMatch(string $topic, string $other) {
		$this->assertFalse($this->createTopic($topic)->match($other));
	}
	
	private function createTopic(string $topic) {
		return new SubscriptionTopic($topic);
	}
	
}
