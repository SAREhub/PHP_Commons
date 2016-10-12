<?php

namespace SAREhub\Commons\Zmq\PublishSubscribe;

use SAREhub\Commons\Misc\Dsn;

/**
 * Represents subscriber ZMQ socket
 */
class Subscriber {
	
	/**
	 * @var Dsn
	 */
	private $dsn = null;
	
	/**
	 * @var array
	 */
	private $topics = [];
	
	/**
	 * @var \ZMQSocket
	 */
	private $socket;
	
	public function __construct(\ZMQContext $context) {
		$this->socket = $context->getSocket(\ZMQ::SOCKET_SUB, null, null);
	}
	
	
	/**
	 * @param \ZMQContext $context
	 * @return Subscriber
	 */
	public static function inContext(\ZMQContext $context) {
		return new self($context);
	}
	
	/**
	 * @param bool $wait
	 * @return null|array
	 */
	public function receive($wait = false) {
		if ($this->isConnected()) {
			$mode = ($wait) ? 0 : \ZMQ::MODE_DONTWAIT;
			$parts = $this->getSocket()->recvMulti($mode);
			
			if ($parts) {
				return ['topic' => $parts[0], 'body' => $parts[1]];
			}
			
			return null;
		}
		
		throw new \LogicException("Can't receive message when socket isn't connect");
	}
	
	/**
	 * @param Dsn $dsn
	 * @return $this
	 */
	public function connect(Dsn $dsn) {
		if ($this->isConnected()) {
			throw new \LogicException("Can't connect on connected socket");
		}
		
		$this->getSocket()->connect((string)$dsn);
		
		$this->dsn = $dsn;
		
		return $this;
	}
	
	/**
	 * @param $topic
	 * @return $this
	 */
	public function subscribe($topic) {
		if (!$this->isSubscribed($topic)) {
			$this->getSocket()->setSockOpt(\ZMQ::SOCKOPT_SUBSCRIBE, $topic);
			$this->topics[$topic] = true;
		}
		
		return $this;
	}
	
	public function unsubscribe($topic) {
		if ($this->isSubscribed($topic)) {
			$this->getSocket()->setSockOpt(\ZMQ::SOCKOPT_UNSUBSCRIBE, $topic);
			unset($this->topics[$topic]);
		}
		
		return $this;
	}
	
	/**
	 * @param string $topic
	 * @return bool
	 */
	public function isSubscribed($topic) {
		return isset($this->topics[$topic]);
	}
	
	/**
	 * @return array
	 */
	public function getTopics() {
		return array_keys($this->topics);
	}
	
	
	/**
	 *
	 */
	public function disconnect() {
		if ($this->isConnected()) {
			$this->getSocket()->disconnect((string)$this->dsn);
			$this->dsn = null;
		}
	}
	
	/**
	 * @return bool
	 */
	public function isConnected() {
		return $this->getDsn() !== null;
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