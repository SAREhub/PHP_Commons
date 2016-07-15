<?php

namespace SAREhub\Commons\Misc;

/**
 * Class for operate on custom configurations
 */
class Parameters implements \JsonSerializable {
	
	/** @var array */
	private $parameters;
	
	public function __construct(array $parameters) {
		$this->parameters = $parameters;
	}
	
	/**
	 * @param string $json
	 * @return self
	 */
	public static function createFromJson($json) {
		return new self(json_decode($json, true));
	}
	
	/**
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($name, $default = null) {
		return ($this->has($name)) ? $this->parameters[$name] : $default;
	}
	
	public function getRequired($name) {
		if ($this->has($name)) {
			return $this->parameters[$name];
		}
		
		throw new \Exception("Required parameter |$name| isn't exists");
	}
	
	/**
	 * @param string $name
	 * @param array $default
	 * @return self
	 * @throws \Exception
	 */
	public function getAsMap($name, array $default = []) {
		$parameters = $this->get($name, $default);
		if (is_array($parameters)) {
			return new self($parameters);
		}
		
		throw new \Exception("Parameter |$name| isn't array");
	}
	
	/**
	 * @param $name
	 * @return self
	 * @throws \Exception
	 */
	public function getRequiredAsMap($name) {
		$parameters = $this->getRequired($name);
		if (is_array($parameters)) {
			return new self($parameters);
		}
		
		throw new \Exception("Parameter |$name| isn't array");
	}
	
	/**
	 * @param $name
	 * @return bool
	 */
	public function has($name) {
		return isset($this->parameters[$name]);
	}
	
	/**
	 * @return array
	 */
	public function getAll() {
		return $this->parameters;
	}
	
	function jsonSerialize() {
		return $this->getAll();
	}
}