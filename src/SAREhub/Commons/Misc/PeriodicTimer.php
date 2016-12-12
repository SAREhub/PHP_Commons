<?php

namespace SAREhub\Commons\Misc;

/**
 * Calls callback in specified time interval(default 1 sec)
 */
class PeriodicTimer {
	
	private $interval = 1;
	private $callback;
	
	private $lastCallTime = 0;
	
	/**
	 * Sets calls time interval
	 * @param int $interval New interval in secs
	 * @return $this
	 */
	public static function every($interval) {
		$timer = new PeriodicTimer();
		$timer->interval = $interval;
		return $timer;
	}
	
	/**
	 * @param callable $callback
	 * @return $this
	 */
	public function call(callable $callback) {
		$this->callback = $callback;
		return $this;
	}
	
	/**
	 * Updates that timer if is after next call its calls callback.
	 * @param int $now
	 */
	public function update($now) {
		if ($now >= ($this->lastCallTime + $this->interval)) {
			$this->lastCallTime = $now;
			$this->callNow();
		}
	}
	
	/**
	 * Calls callback of that timer
	 */
	public function callNow() {
		$c = $this->callback;
		$c();
	}
}