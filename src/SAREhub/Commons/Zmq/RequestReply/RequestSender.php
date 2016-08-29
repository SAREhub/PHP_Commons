<?php

namespace SAREhub\Commons\Zmq\RequestReply;

/**
 * Sending request to ZMQ socket
 */
class RequestSender {
	
	/** @var \ZMQSocket */
	private $socket;
	
	public function __construct(\ZMQSocket $socket) {
		$this->socket = $socket;
	}
	
	/**
	 * @return RequestSenderBuilder
	 */
	public static function builder() {
		return new RequestSenderBuilder();
	}
	
	/**
	 * Send request via ZMQ socket.
	 * @param string $request Request payload.
	 * @param bool $wait If true that operation would be block.
	 * @return $this
	 * @throws \ZMQSocketException
	 */
	public function sendRequest($request, $wait = true) {
		$this->socket->send($request, ($wait)? 0 : \ZMQ::MODE_DONTWAIT);
		return $this;
	}
	
	/**
	 * Receive reply from ZMQ socket.
	 * @param bool $wait If true that operation would be block.
	 * @return bool|string
	 * @throws \ZMQSocketException
	 */
	public function receiveReply($wait = true) {
		return $this->socket->recv(($wait)? 0 : \ZMQ::MODE_DONTWAIT);
	}
	
	/**
	 * @return \ZMQSocket
	 */
	public function getSocket() {
		return $this->socket;
	}
}