<?php

namespace SAREhub\Commons\Zmq\PublishSubscribe;

/**
 * Implementation of zmq forwarder device
 */
class ZmqForwarderDevice
{

    const DEFAULT_TIMEOUT = 5;

    /**
     * @var \ZMQDevice
     */
    private $device;

    public function __construct(\ZMQDevice $device)
    {
        $this->device = $device;
    }

    /**
     * @return ZmqForwarderDeviceBuilder
     */
    public static function getBuilder()
    {
        return new ZmqForwarderDeviceBuilder();
    }

    /**
     * @param callable $callback
     * @param mixed|null $userData
     * @return $this
     */
    public function setTimerCallback(callable $callback, $userData = null)
    {
        $this->device->setTimerCallback($callback, self::DEFAULT_TIMEOUT, $userData);
        return $this;
    }

    /**
     * @param int $timeout
     * @return $this
     */
    public function setTimerTimeout($timeout)
    {
        $this->device->setTimerTimeout($timeout);
        return $this;
    }

    /**
     * @return int
     */
    public function getTimerTimeout()
    {
        return $this->device->getTimerTimeout();
    }

    public function run()
    {
        $this->device->run();
    }
}