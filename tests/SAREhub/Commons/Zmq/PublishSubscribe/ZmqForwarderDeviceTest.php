<?php

use PHPUnit\Framework\TestCase;
use SAREhub\Commons\Zmq\PublishSubscribe\ZmqForwarderDevice;

class ZmqForwarderDeviceTest extends TestCase
{

    private $deviceMock;

    /**
     * @var ZmqForwarderDevice
     */
    private $forwarder;

    protected function setUp()
    {
        parent::setUp();
        $this->deviceMock = $this->createMock(ZMQDevice::class);
        $this->forwarder = new ZmqForwarderDevice($this->deviceMock);
    }

    public function testSetTimerCallbackWhenOnlyCallback()
    {
        $callback = $this->createCallbackMock();
        $this->deviceMock->expects($this->once())->method('setTimerCallback')
            ->with($callback, ZmqForwarderDevice::DEFAULT_TIMEOUT, null);

        $this->forwarder->setTimerCallback($callback);
    }

    public function testSetTimerCallbackWhenCallbackAndData()
    {
        $callback = $this->createCallbackMock();
        $data = ['data'];
        $this->deviceMock->expects($this->once())->method('setTimerCallback')
            ->with($callback, ZmqForwarderDevice::DEFAULT_TIMEOUT, $data);

        $this->forwarder->setTimerCallback($callback, $data);
    }

    public function testSetTimerCallbackThenReturnThis()
    {
        $callback = $this->createCallbackMock();
        $this->assertSame($this->forwarder, $this->forwarder->setTimerCallback($callback));
    }

    public function testSetTimerTimeout()
    {
        $timeout = 10;
        $this->deviceMock->expects($this->once())->method('setTimerTimeout')
            ->with($timeout);
        $this->forwarder->setTimerTimeout($timeout);
    }

    public function testGetTimerTimeout()
    {
        $this->deviceMock->method('getTimerTimeout')->willReturn(10);
        $this->assertEquals(10, $this->forwarder->getTimerTimeout());
    }

    public function testRun()
    {
        $this->deviceMock->expects($this->once())->method('run');
        $this->forwarder->run();
    }

    private function createCallbackMock()
    {
        return $this->createPartialMock(stdClass::class, ['__invoke']);
    }
}
