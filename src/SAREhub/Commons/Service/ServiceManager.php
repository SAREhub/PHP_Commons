<?php


namespace SAREhub\Commons\Service;


class ServiceManager extends ServiceSupport
{
    private $services;

    /**
     * @param Service[] $services
     */
    public function __construct(array $services)
    {
        $this->services = $services;
    }


    protected function doStart()
    {
        foreach ($this->services as $service) {
            $service->start();
        }
    }

    protected function doTick()
    {
        foreach ($this->services as $service) {
            $service->tick();
        }
    }

    protected function doStop()
    {
        foreach ($this->services as $service) {
            $service->stop();
        }
    }

    public function getServices(): array
    {

    }
}