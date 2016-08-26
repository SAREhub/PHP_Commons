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
	 * Send new request via ZMQ socket and waiting for reply.
	 * @param string $request
	 * @return string
	 */
	public function sendRequest($request) {
		$this->socket->send($request);
		return $this->socket->recv();
	}
	
	public function getSocket() {
		return $this->socket;
	}
}