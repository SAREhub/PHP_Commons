<?php

namespace SAREhub\Commons\Misc;


use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use SAREhub\Commons\Test\CallableMock;

class RetryFunctionWrapperTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testExecuteInOnePass()
    {
        $callback = CallableMock::create();

        $callback->expects("__invoke")->andReturn(true);
        $wrapper = new RetryFunctionWrapper($callback);
        $this->assertTrue($wrapper->executeInOnePass());
    }

    public function testExecuteInOnePassWhenExpectedExceptionOccur()
    {
        $callback = CallableMock::create();

        $callback->expects("__invoke")->andThrow(new \InvalidArgumentException());
        $callback->expects("__invoke")->andReturn(true);
        $wrapper = new RetryFunctionWrapper($callback, [\InvalidArgumentException::class]);
        $this->assertTrue($wrapper->executeInOnePass());
    }

    public function testExecuteInOnePassWhenUsedAllAttempts()
    {
        $callback = CallableMock::create();

        $callback->expects("__invoke")->andThrow(new \InvalidArgumentException());
        $callback->expects("__invoke")->andThrow(new \InvalidArgumentException());
        $callback->expects("__invoke")->never();
        $wrapper = new RetryFunctionWrapper($callback, [\InvalidArgumentException::class], 2);

        $this->expectException(\InvalidArgumentException::class);
        $wrapper->executeInOnePass();
    }
}
