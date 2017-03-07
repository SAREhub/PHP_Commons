<?php

namespace SAREhub\Commons\Process;

if (PcntlSignals::isSupported()) {
	define('PcntlSignals_SIGHUP', \SIGHUP);
	define('PcntlSignals_SIGINT', \SIGINT);
	define('PcntlSignals_SIGTERM', \SIGTERM);
	define('PcntlSignals_SIGPIPE', \SIGPIPE);
	define('PcntlSignals_SIGUSR1', \SIGUSR1);
} else {
	define('PcntlSignals_SIGHUP', 1);
	define('PcntlSignals_SIGINT', 2);
	define('PcntlSignals_SIGTERM', 15);
	define('PcntlSignals_SIGPIPE', 13);
	define('PcntlSignals_SIGUSR1', 10);
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
	
	const SIGHUP = PcntlSignals_SIGHUP;
	const SIGINT = PcntlSignals_SIGINT;
	const SIGTERM = PcntlSignals_SIGTERM;
	const SIGPIPE = PcntlSignals_SIGPIPE;
	const SIGUSR1 = PcntlSignals_SIGUSR1;
	
	const DEFAULT_NAMESPACE = 'default';
	
	private $handlers = [];
	
	private function __construct() {
		
	}
	
	/**
	 * @param callable|null $signalInstaller
	 * @return PcntlSignals
	 */
	public static function create($signalInstaller = null) {
		$signalInstaller = $signalInstaller ? $signalInstaller : self::getDefaultSignalInstaller();
		$instance = new PcntlSignals();
		$signals = [self::SIGHUP, self::SIGINT, self::SIGTERM, self::SIGPIPE, self::SIGUSR1];
		$handler = [$instance, 'dispatchSignal'];
		foreach ($signals as $signal) {
			$signalInstaller($signal, $handler);
		}
		return $instance;
	}
	
	public static function getDefaultSignalInstaller() {
		if (self::isSupported()) {
			return function ($signal, $handler) {
				pcntl_signal($signal, $handler);
			};
		} else {
			return function ($signal, $handler) {
				
			};
		}
	}
	
	/**
	 * Registers handler for signal in selected namespace.
	 * @param int $signal Signal id (\SIG* constants)
	 * @param callable $handler Handler callback
	 * @param string $namespace
	 * @return $this
	 */
	public function handle($signal, $handler, $namespace = self::DEFAULT_NAMESPACE) {
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
	public function checkPendingSignals() {
		if (self::isSupported()) {
			pcntl_signal_dispatch();
		}
	}
	
	/**
	 * @return bool
	 */
	public static function isSupported() {
		return !defined('PHP_WINDOWS_VERSION_MAJOR');
	}
}