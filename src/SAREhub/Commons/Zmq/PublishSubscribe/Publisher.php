<?php

namespace SAREhub\Commons\Zmq\PublishSubscribe;

use SAREhub\Commons\Zmq\ZmqSocketSupport;

/**
 * Represents publisher ZMQ socket
 */
class Publisher extends ZmqSocketSupport {
	
	public function __construct(\ZMQSocket $socket) {
		parent::__construct($socket);
	}
	
	/**
	 * @param \ZMQContext $context
	 * @return Publisher
	 */
	public static function inContext(\ZMQContext $context) {
		return new self($context->getSocket(\ZMQ::SOCKET_PUB, null, null));
	}
	
	/**
	 * @param string $topic
	 * @param string $message
	 * @param bool $wait
	 */
	public function publish($topic, $message, $wait = false) {
		if (!$this->isBindedOrConnected()) {
			throw new \LogicException("Publishing message when not binded or connected");
		}

		$mode = ($wait) ? 0 : \ZMQ::MODE_DONTWAIT;
		$this->getSocket()->sendmulti([$topic, $message], $mode);
	}
}