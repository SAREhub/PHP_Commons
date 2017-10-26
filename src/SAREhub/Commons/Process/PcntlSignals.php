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
class PcntlSignals
{

    const SIGHUP = 1;
    const SIGINT = 2;
    const SIGTERM = 15;
    const SIGPIPE = 13;
    const SIGUSR1 = 10;

    const DEFAULT_NAMESPACE = 'default';

    private $handlers = [];

    /**
     * Factory method, can install default signals.
     * @param bool $install When true installs signals.
     * @return PcntlSignals
     */
    public static function create($install = true)
    {
        $instance = new PcntlSignals();
        if ($install) {
            $instance->install(self::getDefaultInstalledSignals());
        }
        return $instance;
    }

    public static function getDefaultInstalledSignals()
    {
        return [self::SIGHUP, self::SIGINT, self::SIGTERM, self::SIGPIPE, self::SIGUSR1];
    }

    /**
     * Installs selected signal
     * @param array $signals
     */
    public function install(array $signals)
    {
        if (self::isSupported()) {
            $callback = [$this, 'dispatchSignal'];
            foreach ($signals as $signal) {
                \pcntl_signal($signal, $callback);
            }
        };
    }

    /**
     * Registers handler for signal in selected namespace.
     * @param int $signal Signal id (\SIG* constants)
     * @param callable $handler Handler callback
     * @param string $namespace
     * @return $this
     */
    public function handle($signal, $handler, $namespace = self::DEFAULT_NAMESPACE)
    {
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
    public function dispatchSignal($signal)
    {
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
    public function getHandlers()
    {
        return $this->handlers;
    }

    public function getHandlersForSignal($signal, $namespace = null)
    {
        return isset($this->handlers[$signal]) ? $this->handlers[$signal] : [];
    }

    /**
     * Calls pcntl_signal_dispatch for process pending signals.
     */
    public function checkPendingSignals()
    {
        if (self::isSupported()) {
            \pcntl_signal_dispatch();
        }
    }

    /**
     * @return bool
     */
    public static function isSupported()
    {
        return !defined('PHP_WINDOWS_VERSION_MAJOR');
    }
}