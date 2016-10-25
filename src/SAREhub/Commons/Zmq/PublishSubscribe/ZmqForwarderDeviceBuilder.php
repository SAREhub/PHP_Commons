<?php

namespace SAREhub\Commons\Zmq\PublishSubscribe;

class ZmqForwarderDeviceBuilder {
	
	/**
	 * @var Subscriber
	 */
	private $frontend;
	
	/**
	 * @var Publisher
	 */
	private $backend;
	
	/**
	 * @var callable
	 */
	private $timerCallback;
	
	/**
	 * @param Subscriber $subscriber
	 * @return $this
	 */
	public function frontend(Subscriber $subscriber) {
		$this->frontend = $subscriber;
		return $this;
	}
	
	/**
	 * @param Publisher $publisher
	 * @return $this
	 */
	public function backend(Publisher $publisher) {
		$this->backend = $publisher;
		return $this;
	}
	
	public function build() {
		if (empty($this->frontend->getTopics())) {
			$this->frontend->subscribe('');
		}
		
		$device = new \ZMQDevice($this->frontend->getSocket(), $this->backend->getSocket());
		return new ZmqForwarderDevice($device);
	}
}