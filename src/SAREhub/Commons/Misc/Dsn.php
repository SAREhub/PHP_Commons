<?php

namespace SAREhub\Commons\Misc;

/**
 * Helper builder for DSN string format: <transport>://endpoint
 */
class Dsn {
	
	/** @var string */
	private $transport;
	
	/** @var string */
	private $endpoint;
	
	/**
	 * Sets INPROC transport type
	 * @return Dsn
	 */
	public static function inproc() {
		return (new self())->transport('inproc');
	}
	
	/**
	 * Sets IPC transport type
	 * @return Dsn
	 */
	public static function ipc() {
		return (new self())->transport('ipc');
	}
	
	/**
	 * Sets TCP transport type
	 * @return Dsn
	 */
	public static function tcp() {
		return (new self())->transport('tcp');
	}
	
	/**
	 * Sets custom transport type
	 * @param $type
	 * @return $this
	 */
	public function transport($type) {
		$this->transport = $type;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getTransport() {
		return $this->transport;
	}
	
	/**
	 * Sets address for dsn
	 * @param string $endpoint
	 * @return $this
	 */
	public function endpoint($endpoint) {
		$this->endpoint = $endpoint;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getEndpoint() {
		return $this->endpoint;
	}
	
	/**
	 * Returns string in DSN format <transport>://endpoint
	 * @return string
	 */
	public function __toString() {
		return $this->transport.'://'.$this->endpoint;
	}
}