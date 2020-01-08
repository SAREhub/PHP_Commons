<?php

namespace Commons\Time;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use SAREhub\Commons\Time\PeriodicTaskDefinition;
use SAREhub\Commons\Time\TimerService;
use PHPUnit\Framework\TestCase;
use SAREhub\Commons\Time\TimerServicePeriodicTasksRegisterTask;

class TimerServicePeriodicTasksRegisterTaskTest extends TestCase
{

    use MockeryPHPUnitIntegration;

    public function testRun()
    {
        $timerService = \Mockery::mock(TimerService::class);
        $id = "test";
        $interval = 60;
        $callback = function () {
        };
        $def = new PeriodicTaskDefinition($id, $interval, $callback);
        $task = new TimerServicePeriodicTasksRegisterTask($timerService, [$def]);

        $timerService->expects("addPeriodicTask")->with($id, $interval, $callback);

        $task->run();
    }
}
