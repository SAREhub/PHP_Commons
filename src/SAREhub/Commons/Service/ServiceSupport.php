<?php

namespace SAREhub\Commons\Service;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class ServiceSupport implements Service
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    private $started = false;
    private $stopped = true;

    public function start()
    {
        if (!$this->isStarted()) {
            $this->getLogger()->notice('service starting ...');
            $this->doStart();
            $this->started = true;
            $this->stopped = false;
            $this->getLogger()->notice('service started');
        }
    }

    public function tick()
    {
        try {
            if ($this->isRunning()) {
                $this->doTick();
            }
        } catch (\Exception $e) {
            try {
                $this->stop();
            } catch (\Exception $e2) {
                // we wants only original exception
            }

            throw $e;
        }
    }

    public function stop()
    {
        if ($this->isStarted()) {
            try {
                $this->getLogger()->notice('service stopping ...');
                $this->doStop();
            } finally {
                $this->started = false;
                $this->stopped = true;
                $this->getLogger()->notice('service stopped');
            }
        }
    }

    public function isStarted()
    {
        return $this->started;
    }

    public function isStopped()
    {
        return $this->stopped;
    }

    public function isRunning()
    {
        return $this->started;
    }

    public function getLogger()
    {
        if ($this->logger === null) {
            $this->logger = new NullLogger();
        }

        return $this->logger;
    }

    public function setLogger(LoggerInterface $logger)
    {
        if ($this->getLogger() === $logger) {
            throw new \LogicException('set same logger instance');
        }

        $this->logger = $logger;
    }

    /**
     * Contains custom worker start logic
     */
    protected function doStart()
    {

    }

    /**
     * Contains custom worker tick logic
     */
    protected function doTick()
    {

    }

    /**
     * Contains custom worker stop logic
     */
    protected function doStop()
    {

    }
}
