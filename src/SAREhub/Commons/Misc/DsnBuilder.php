<?php

namespace SAREhub\Commons\Misc;

/**
 * Helper builder for DSN string format: <transport>://endpoint
 */
class DsnBuilder {
	
	/** @var string */
	private $transportType;
	
	/** @var string */
	private $endpoint;
	
	/**
	 * Sets INPROC transport type
	 * @return DsnBuilder
	 */
	public static function inproc() {
		$builder = new self();
		return $builder->transport('inproc');
	}
	
	/**
	 * Sets IPC transport type
	 * @return DsnBuilder
	 */
	public static function ipc() {
		$builder = new self();
		return $builder->transport('ipc');
	}
	
	/**
	 * Sets TCP transport type
	 * @return DsnBuilder
	 */
	public static function tcp() {
		$builder = new self();
		return $builder->transport('tcp');
	}
	
	/**
	 * Sets custom transport type
	 * @param $type
	 * @return $this
	 */
	public function transport($type) {
		$this->transportType = $type;
		return $this;
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
	 * Returns string in DSN format <transport>://endpoint
	 * @return string
	 */
	public function __toString() {
		return $this->transportType.'://'.$this->endpoint;
	}
}