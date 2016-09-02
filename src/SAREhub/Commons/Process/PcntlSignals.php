<?php

namespace SAREhub\Commons\Process;

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
		$handler = function ($signal) {
			$this->dispatchSignal($signal);
		};
		
		\pcntl_signal(\SIGHUP, $handler);
		\pcntl_signal(\SIGINT, $handler);
		\pcntl_signal(\SIGTERM, $handler);
		\pcntl_signal(\SIGPIPE, $handler);
		\pcntl_signal(\SIGUSR1, $handler);
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
	public function checkPendingSignals() {
		pcntl_signal_dispatch();
	}
}