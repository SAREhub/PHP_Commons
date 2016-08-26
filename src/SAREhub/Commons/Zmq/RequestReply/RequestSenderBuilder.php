<?php

namespace SAREhub\Commons\Zmq\RequestReply;

class RequestSenderBuilder {
	
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
	public function connect($dsn) {
		$this->dsn = $dsn;
		return $this;
	}
	
	/**
	 * @return RequestSender
	 */
	public function build() {
		$socket = $this->zmqContext->getSocket(\Zmq::SOCKET_REQ, null, null)->connect((string)$this->dsn);
		return new RequestSender($socket);
	}
	
}