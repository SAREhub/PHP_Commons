<?php

namespace SAREhub\Commons\Process;

if (PcntlSignals::isSupported()) {
	echo "test";
	define('SIGHUP', \SIGHUP);
	define('SIGINT', \SIGINT);
	define('SIGTERM', \SIGTERM);
	define('SIGPIPE', \SIGPIPE);
	define('SIGUSR1', \SIGUSR1);
} else {
	define('SIGHUP', 1);
	define('SIGINT', 2);
	define('SIGTERM', 15);
	define('SIGPIPE', 13);
	define('SIGUSR1', 10);
}

/**
 * Helper class for handling linux signals.
 * Installed signals:
 * SIGHUP
 * SIGINT
 * SIGTERM
 * SIGPIPE
 * SIGUSR1
 */
class PcntlSignals {
	
	const SIGHUP = SIGHUP;
	const SIGINT = SIGINT;
	const SIGTERM = SIGTERM;
	const SIGPIPE = SIGPIPE;
	const SIGUSR1 = SIGUSR1;
	
	const DEFAULT_NAMESPACE = 'default';
	
	protected $handlers = [];
	
	private static $instance = null;
	
	public function __construct() {
		
	}
	
	/**
	 * @return PcntlSignals
	 */
	public static function getGlobal() {
		if (self::$instance === null) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	/**
	 * Installs pcntl signals.
	 */
	public function install() {
		if (self::isSupported()) {
			$handler = function ($signal) {
				$this->dispatchSignal($signal);
			};
			
			\pcntl_signal(self::SIGHUP, $handler);
			\pcntl_signal(self::SIGINT, $handler);
			\pcntl_signal(self::SIGTERM, $handler);
			\pcntl_signal(self::SIGPIPE, $handler);
			\pcntl_signal(self::SIGUSR1, $handler);
		}
	}
	
	/**
	 * Registers handler for signal in selected namespace.
	 * @param int $signal Signal id (\SIG* constants)
	 * @param callable $handler Handler callback
	 * @param string $namespace
	 * @return $this
	 */
	public function handle($signal, callable $handler, $namespace = self::DEFAULT_NAMESPACE) {
		if (empty($this->handlers[$signal])) {
			$this->handlers[$signal] = [];
		}
		
		if (empty($this->handlers[$signal][$namespace])) {
			$this->handlers[$signal][$namespace] = [];
		}
		
		$this->handlers[$signal][$namespace][] = $handler;
		
		return $this;
	}
	
	/**
	 * Dispatch signal on registered handlers.
	 * @param int $signal
	 * @return $this
	 */
	public function dispatchSignal($signal) {
		if (isset($this->handlers[$signal])) {
			foreach ($this->handlers[$signal] as $namespaceHandlers) {
				foreach ($namespaceHandlers as $handler) {
					$handler();
				}
			}
		}
		
		return $this;
	}
	
	/**
	 * Returns all registered handlers.
	 * @return array
	 */
	public function getHandlers() {
		return $this->handlers;
	}
	
	public function getHandlersForSignal($signal) {
		return isset($this->handlers[$signal]) ? $this->handlers[$signal] : [];
	}
	
	/**
	 * Calls pcntl_signal_dispatch for process pending signals.
	 */
	public static function checkPendingSignals() {
		if (self::isSupported()) {
			\pcntl_signal_dispatch();
		}
	}
	
	/**
	 * @return bool
	 */
	public static function isSupported() {
		return !defined('PHP_WINDOWS_VERSION_MAJOR');
	}
}