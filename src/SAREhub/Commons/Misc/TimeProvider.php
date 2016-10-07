<?php

namespace SAREhub\Commons\Misc;


/**
 * Time provider for better control time operations with time frezing future.
 */
class TimeProvider {
	
	protected $frozenTime = null;
	
	private static $instance = null;
	
	/**
	 * @return TimeProvider
	 */
	public static function get() {
		if (!self::$instance) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	/**
	 * Returns current time or frozen time when is defined
	 * @return int
	 */
	public function now() {
		return $this->hasFrezenTime() ? $this->frozenTime : time();
	}
	
	/**
	 * Sets const time, now() call will return 'frozen' time
	 * @param null|int $now When null sets time from now() call as 'frozenTime'
	 * @return int Returns frozen time value
	 */
	public function freezeTime($now = null) {
		return $this->frozenTime = ($now === null ? $this->now() : $now);
	}
	
	/**
	 * Unfrezze time, now() call will return current time
	 */
	public function unfreezeTime() {
		$this->frozenTime = null;
	}
	
	/**
	 * @return bool
	 */
	public function hasFrezenTime() {
		return $this->frozenTime !== null;
	}
	
	/**
	 * @return null|int
	 */
	public function getFrezenTime() {
		return $this->frozenTime;
	}
	
	
}