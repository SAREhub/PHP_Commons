<?php

namespace SAREhub\Commons\Zmq\RequestReply;

/**
 * Receiving request from ZMQ and sending reply for that.
 */
class RequestReceiver {
	
	/** @var \ZMQSocket */
	protected $socket;
	
	/**
	 * @param \ZMQSocket $socket
	 */
	public function __construct(\ZMQSocket $socket) {
		$this->socket = $socket;
	}
	
	/**
	 * @return RequestReceiverBuilder
	 */
	public static function builder() {
		return new RequestReceiverBuilder();
	}
	
	/**
	 * Getting next request from socket.
	 * @param bool $wait If true call will waits for next request.
	 * @return string
	 * @throws \ZMQSocketException
	 */
	public function receiveRequest($wait = true) {
		return $this->socket->recv(($wait ? 0 : \ZMQ::MODE_DONTWAIT));
	}
	
	/**
	 * Sending reply to ZMQ socket.
	 * @param string $reply
	 * @param bool $wait If true call will be waits for reply send done.
	 * @return $this
	 * @throws \ZMQSocketException
	 */
	public function sendReply($reply, $wait = true) {
		$this->socket->send($reply, ($wait ? 0 : \ZMQ::MODE_DONTWAIT));
		return $this;
	}
}