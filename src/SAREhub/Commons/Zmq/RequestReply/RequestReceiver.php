<?php

namespace SAREhub\Commons\Zmq\RequestReply;

/**
 * Receiving request from ZMQ and sending reply for that.
 */
class RequestReceiver {
	
	/** @var \ZMQSocket */
	protected $socket;
	
	public function __construct(\ZMQSocket $socket) {
		$this->socket = $socket;
	}
	
	/**
	 * Getting next request from socket
	 */
	public function receiveRequest() {
		return $this->socket->recv();
	}
	
	/**
	 * Sending reply to ZMQ socket
	 * @param string $reply
	 */
	public function sendReply($reply) {
		$this->socket->send($reply);
	}
	
	public static function builder() {
		return new RequestReceiverBuilder();
	}
}