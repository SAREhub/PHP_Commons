<?php

namespace SAREhub\Commons\Zmq\RequestReply;

use SAREhub\Commons\Misc\Dsn;

/**
 * Receiving request from ZMQ and sending reply for that.
 */
class RequestReceiver {
	
	const WAIT = true;
	const DONT_WAIT = false;
	
	/**
	 * @var Dsn
	 */
	protected $dsn = null;
	
	/**
	 * @var \ZMQContext
	 */
	protected $context;
	
	/**
	 * @var \ZMQSocket
	 */
	protected $socket = null;
	
	/**
	 * @param \ZMQContext $context
	 */
	public function __construct(\ZMQContext $context) {
		$this->context = $context;
	}
	
	/**
	 * @param \ZMQContext $context
	 * @return RequestReceiver
	 */
	public static function inContext(\ZMQContext $context) {
		return new self($context);
	}
	
	/**
	 * Binds socket to $dsn
	 * @param Dsn $dsn
	 * @return $this
	 * @throws \LogicException When binding second time.
	 */
	public function bind(Dsn $dsn) {
		if ($this->isBinded()) {
			throw new \LogicException("Can't bind to binded socket");
		}
		$this->dsn = $dsn;
		$this->getSocket()->bind((string)$dsn);
		return $this;
	}
	
	/**
	 * Unbinds socket from current binded dsn
	 * @return $this
	 */
	public function unbind() {
		if ($this->isBinded()) {
			$this->getSocket()->unbind($this->dsn);
			$this->dsn = null;
		}
		
		return $this;
	}
	
	/**
	 * Getting next request from socket.
	 * @param bool $wait If true call will waits for next request.
	 * @return string
	 * @throws \ZMQSocketException
	 */
	public function receiveRequest($wait = self::WAIT) {
		return $this->getSocket()->recv(($wait ? 0 : \ZMQ::MODE_DONTWAIT));
	}
	
	/**
	 * Sending reply to ZMQ socket.
	 * @param string $reply
	 * @param bool $wait If true call will be waits for reply send done.
	 * @return $this
	 * @throws \ZMQSocketException
	 */
	public function sendReply($reply, $wait = self::WAIT) {
		$this->getSocket()->send($reply, ($wait ? 0 : \ZMQ::MODE_DONTWAIT));
		return $this;
	}
	
	/**
	 * @return bool
	 */
	public function isBinded() {
		return $this->getDsn() !== null;
	}
	
	/**
	 * @return Dsn Current binded dsn
	 */
	public function getDsn() {
		return $this->dsn;
	}
	
	/**
	 * @return \ZMQSocket
	 */
	public function getSocket() {
		if ($this->socket === null) {
			$this->socket = $this->context->getSocket(\ZMQ::SOCKET_REP, null, null);
		}
		
		return $this->socket;
	}
	
	/**
	 * @return \ZMQContext
	 */
	public function getContext() {
		return $this->context;
	}
	
}