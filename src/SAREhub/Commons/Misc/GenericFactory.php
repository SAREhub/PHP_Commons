<?php

namespace SAREhub\Commons\Misc;

/**
 * Factory with registry of available creators
 */
class GenericFactory {
	
	/** @var array */
	private $creators = [];
	
	/**
	 * @param string $creatorName
	 * @param mixed $data
	 * @return mixed
	 * @throws \Exception
	 */
	public function create($creatorName, $data = null) {
		if ($creator = $this->getCreator($creatorName)) {
			return $creator($data);
		}
		
		throw new \Exception("creator with name '".$creatorName."' not registered");
	}
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasCreator($name) {
		return isset($this->creators[$name]);
	}
	
	/**
	 * @param string $name
	 * @return callable|null
	 */
	public function getCreator($name) {
		return $this->hasCreator($name) ? $this->creators[$name] : null;
	}
	
	/**
	 * @param string $name
	 * @param callable $creator
	 * @return self
	 */
	public function registerCreator($name, callable $creator) {
		$this->creators[$name] = $creator;
		return $this;
	}
	
	/**
	 * @param array $creators
	 * @return self
	 */
	public function registerCreators(array $creators) {
		foreach ($creators as $name => $creator) {
			$this->registerCreator($name, $creator);
		}
		
		return $this;
	}
	
	
}