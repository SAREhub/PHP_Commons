<?php

namespace SAREhub\Commons\Time;

use Cron\CronExpression;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use SAREhub\Commons\Misc\TimeProvider;
use SAREhub\Commons\Task\Task;

class CronTaskTest extends TestCase
{

    use MockeryPHPUnitIntegration;

    /**
     * @var CronExpression
     */
    private $cron;

    /**
     * @var TimeProvider
     */
    private $timeProvider;

    /**
     * @var Task
     */
    private $taskToRun;

    /**
     * @var CronTask
     */
    private $task;

    protected function setUp()
    {
        $this->cron = CronExpression::factory("@hourly");
        $this->timeProvider = new TimeProvider();
        $this->timeProvider->freezeTime();
        $this->taskToRun = Mockery::mock(Task::class);
        $this->task = new CronTask($this->cron, $this->timeProvider, $this->taskToRun);
    }

    public function testRunWhenNowIsNextRunDate()
    {
        $currentNextRunDate = $this->cron->getNextRunDate("@" . $this->timeProvider->now());
        $this->timeProvider->freezeTime($currentNextRunDate->getTimestamp());

        $this->taskToRun->expects("run")->with();

        ($this->task)();
    }

    public function testRunWhenNowIsNotNextRunDate()
    {
        $currentNextRunDate = $this->cron->getNextRunDate("@" . $this->timeProvider->now());
        $this->timeProvider->freezeTime($currentNextRunDate->getTimestamp() - 1);

        $this->taskToRun->expects("run")->never();

        ($this->task)();
    }

    public function testRunWhenAfterRunDate()
    {
        $currentNextRunDate = $this->cron->getNextRunDate("@" . $this->timeProvider->now());
        $this->timeProvider->freezeTime($currentNextRunDate->getTimestamp() + 1);
        $this->taskToRun->expects("run")->once();
        ($this->task)();

        $currentNextRunDate = $this->cron->getNextRunDate("@" . $this->timeProvider->now());
        $this->timeProvider->freezeTime($currentNextRunDate->getTimestamp() - 1);
        $this->taskToRun->expects("run")->never();

        ($this->task)();
    }
}
