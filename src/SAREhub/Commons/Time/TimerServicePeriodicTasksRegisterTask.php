<?php


namespace SAREhub\Commons\Time;


use SAREhub\Commons\Task\Task;
use SAREhub\Commons\Time\TimerService;

class TimerServicePeriodicTasksRegisterTask implements Task
{
    /**
     * @var TimerService
     */
    private $service;

    /**
     * @var PeriodicTaskDefinition[]
     */
    private $taskDefs;

    public function __construct(TimerService $service, array $taskDefs)
    {
        $this->service = $service;
        $this->taskDefs = $taskDefs;
    }

    public function run()
    {
        foreach ($this->taskDefs as $taskDef) {
            $this->service->addPeriodicTask(
                $taskDef->getId(),
                $taskDef->getInterval(),
                $taskDef->getCallback()
            );
        }
    }
}
