<?php

namespace SAREhub\Commons\Process;

use PHPUnit\Framework\TestCase;


class PcntlSignalsTest extends TestCase
{

    /** @var PcntlSignals */
    private $signals;

    protected function setUp()
    {
        $this->signals = PcntlSignals::create(false);
    }

    public function testHandle()
    {
        $handlerMock = $this->createSignalHandler();
        $this->signals->handle(1, $handlerMock);
        $this->assertEquals([1 => [PcntlSignals::DEFAULT_NAMESPACE => [$handlerMock]]], $this->signals->getHandlers());
    }

    public function testHandleSameSignal()
    {
        $handlerMock = $this->createSignalHandler();
        $this->signals->handle(2, $handlerMock);
        $handlerMock2 = $this->createSignalHandler();
        $this->signals->handle(2, $handlerMock2);

        $this->assertEquals([
            2 => [
                PcntlSignals::DEFAULT_NAMESPACE => [$handlerMock, $handlerMock2]
            ]
        ], $this->signals->getHandlers());
    }

    public function testGetHandlersForSignal()
    {
        $handlerMock = $this->createSignalHandler();
        $this->signals->handle(2, $handlerMock);
        $this->signals->handle(5, $this->createSignalHandler());
        $this->assertEquals([
            PcntlSignals::DEFAULT_NAMESPACE => [$handlerMock]
        ], $this->signals->getHandlersForSignal(2));
    }

    public function testDispatchSignal()
    {
        $handlerMock = $this->createSignalHandler();
        $handlerMock->expects($this->once())->method('__invoke');
        $this->signals->handle(2, $handlerMock);
        $this->signals->dispatchSignal(2);
    }

    private function createSignalHandler()
    {
        return $this->createPartialMock(\stdClass::class, ['__invoke']);
    }
}
