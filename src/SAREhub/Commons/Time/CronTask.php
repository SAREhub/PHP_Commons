<?php


namespace SAREhub\Commons\Time;


use Cron\CronExpression;
use DateTimeInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SAREhub\Commons\Misc\TimeProvider;
use SAREhub\Commons\Task\Task;

class CronTask implements LoggerAwareInterface
{
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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var DateTimeInterface
     */
    private $nextRunDate;

    public function __construct(CronExpression $cron, TimeProvider $timeProvider, Task $taskToRun)
    {
        $this->cron = $cron;
        $this->timeProvider = $timeProvider;
        $this->taskToRun = $taskToRun;
        $this->logger = new NullLogger();
        $this->updateNextRunDate();
    }

    public function __invoke()
    {
        $now = $this->timeProvider->now();
        $this->logger->debug("Invoked, current nextRunDate: {nextRunDate}", [
            "nextRunDate" => $this->nextRunDate->format(\DateTime::ATOM)
        ]);
        if ($now >= $this->nextRunDate->getTimestamp()) {
            $this->logger->notice("Run task, current nextRunDate: {nextRunDate}", [
                "nextRunDate" => $this->nextRunDate->format(\DateTime::ATOM)
            ]);
            $this->taskToRun->run();
            $this->updateNextRunDate();
            $this->logger->notice("Task ended, current nextRunDate: {nextRunDate}", [
                "nextRunDate" => $this->nextRunDate->format(\DateTime::ATOM)
            ]);
        }
    }

    private function updateNextRunDate(): void
    {
        $this->nextRunDate = $this->cron->getNextRunDate("@" . $this->timeProvider->now());
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
