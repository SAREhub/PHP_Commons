<?php

namespace SAREhub\Commons\Zmq;


use SAREhub\Commons\Misc\Dsn;

abstract class ZmqSocketSupport {

	/**
	 * @var Dsn
	 */
	private $binded = null;

	/**
	 * @var Dsn[]
	 */
	private $connections = [];

	/**
	 * @var \ZMQSocket
	 */
	private $socket;

	public function __construct(\ZMQSocket $socket) {
		$this->socket = $socket;
	}

	/**
	 * @param Dsn $dsn
	 * @return $this
	 * @throws \LogicException, \ZMQException
	 */
	public function bind(Dsn $dsn) {
		if ($this->isBinded()) {
			throw new \LogicException("socket can't be bind second time");
		}

		$this->getSocket()->bind((string)$dsn);
		$this->binded = $dsn;

		return $this;
	}

	/**
	 * @throws \ZMQException
	 */
	public function unbind() {
		if ($this->isBinded()) {
			$this->getSocket()->unbind((string)$this->getBinded());
			$this->binded = null;
		}

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isBinded() {
		return $this->binded !== null;
	}

	/**
	 * @param Dsn $dsn
	 * @return $this
	 */
	public function connect(Dsn $dsn) {
		if (!$this->isConnectedTo($dsn)) {
			$this->getSocket()->connect((string)$dsn);
			$this->connections[(string)$dsn] = $dsn;
		}

		return $this;
	}

	/**
	 * @param Dsn $dsn
	 * @return $this
	 */
	public function disconnect(Dsn $dsn) {
		if ($this->isConnectedTo($dsn)) {
			$this->getSocket()->disconnect((string)$dsn);
			unset($this->connections[(string)$dsn]);
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	public function disconnectAll() {
		foreach ($this->getConnections() as $connection) {
			$this->disconnect($connection);
		}

		return $this;
	}


	/**
	 * @return bool
	 */
	public function isConnectedToAny() {
		return !empty($this->getConnections());
	}

	/**
	 * @param Dsn $dsn
	 * @return bool
	 */
	public function isConnectedTo(Dsn $dsn) {
		return isset($this->connections[(string)$dsn]);
	}

	public function isBindedOrConnected() {
		return $this->isBinded() || $this->isConnectedToAny();
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
	public function getBinded() {
		return $this->binded;
	}

	/**
	 * @return Dsn[]
	 */
	public function getConnections() {
		return $this->connections;
	}
}