<?php

namespace SAREhub\Commons\Zmq\RequestReply;

class RequestReceiverBuilder {
	
	/** @var \ZMQContext */
	private $zmqContext;
	
	/** @var string */
	private $dsn;
	
	/**
	 * @param \ZMQContext $zmqContext
	 * @return $this
	 */
	public function context(\ZMQContext $zmqContext) {
		$this->zmqContext = $zmqContext;
		return $this;
	}
	
	/**
	 * @param mixed $dsn
	 * @return $this
	 */
	public function bind($dsn) {
		$this->dsn = $dsn;
		return $this;
	}
	
	/**
	 * @return RequestReceiver
	 */
	public function build() {
		$socket = $this->zmqContext->getSocket(\Zmq::SOCKET_REP, null, null)->bind((string)$this->dsn);
		return new RequestReceiver($socket);
	}
}