<?php

namespace SAREhub\Commons\Service;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;

class ServiceManagerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var Mock | Service
     */
    private $service;

    /**
     * @var ServiceManager
     */
    private $manager;

    protected function setUp()
    {
        $this->service = \Mockery::mock(Service::class)->shouldIgnoreMissing();
        $this->manager = new ServiceManager([$this->service]);
    }

    public function testStart()
    {
        $this->service->expects("start");
        $this->manager->start();
    }

    public function testTick()
    {
        $this->manager->start();

        $this->service->expects("tick");
        $this->manager->tick();
    }

    public function testStop()
    {
        $this->manager->start();

        $this->service->expects("stop");
        $this->manager->stop();
    }

}
