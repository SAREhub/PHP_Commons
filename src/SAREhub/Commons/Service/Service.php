<?php

namespace SAREhub\Commons\Service;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

interface Service extends LoggerAwareInterface
{

    /**
     * Executed for start service.
     * @throws \Exception When something was wrong.
     */
    public function start();

    /**
     * Executed on every service tick.
     * @throws \Exception When something was wrong.
     */
    public function tick();

    /**
     * Executed for stop service
     * @throws \Exception When something was wrong.
     */
    public function stop();

    /**
     * @return boolean
     */
    public function isStarted();

    /**
     * @return boolean
     */
    public function isStopped();

    /**
     * @return boolean
     */
    public function isRunning();

    /**
     * @return LoggerInterface
     */
    public function getLogger();
}