<?php

namespace SAREhub\Commons\Zmq\PublishSubscribe;

use SAREhub\Commons\Misc\Dsn;

/**
 * Represents publisher ZMQ socket
 */
class Publisher {
	
	/**
	 * @var Dsn
	 */
	private $dsn = null;
	
	/**
	 * @var \ZMQSocket
	 */
	private $socket;
	
	public function __construct(\ZMQContext $context) {
		$this->socket = $context->getSocket(\ZMQ::SOCKET_PUB, null, null);
	}
	
	/**
	 * @param \ZMQContext $context
	 * @return Publisher
	 */
	public static function inContext(\ZMQContext $context) {
		return new self($context);
	}
	
	/**
	 * @param string $topic
	 * @param string $message
	 * @param bool $wait
	 */
	public function publish($topic, $message, $wait = false) {
		if (!$this->isBinded()) {
			throw new \LogicException("Can't publish message on unbined socket");
		}
		$mode = ($wait) ? 0 : \ZMQ::MODE_DONTWAIT;
		$this->getSocket()->sendmulti([$topic, $message], $mode);
	}
	
	/**
	 * @param Dsn $dsn
	 * @return $this
	 * @throws \LogicException, \ZMQException
	 */
	public function bind(Dsn $dsn) {
		if ($this->isBinded()) {
			throw new \LogicException("Can't bind binded socket");
		}
		
		$this->socket->bind((string)$dsn);
		$this->dsn = $dsn;
		
		return $this;
	}
	
	/**
	 * @throws \ZMQException
	 */
	public function unbind() {
		if ($this->isBinded()) {
			$this->socket->unbind((string)$this->getDsn());
			$this->dsn = null;
		}
	}
	
	/**
	 * @return bool
	 */
	public function isBinded() {
		return $this->dsn !== null;
	}
	
	/**
	 * @return \ZMQSocket
	 */
	public function getSocket() {
		return $this->socket;
	}
	
	/**
	 * @return Dsn
	 */
	public function getDsn() {
		return $this->dsn;
	}
}