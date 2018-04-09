<?php

namespace SAREhub\Commons\Task;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class SequenceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testRun()
    {
        $task = \Mockery::mock(Task::class);
        $seq = new Sequence([$task]);

        $task->expects("run");

        $seq->run();
    }
}
